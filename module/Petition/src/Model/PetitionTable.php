<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Petition\Model;

use I18n\Model\I18nModel;
use Zend\I18n\Translator\TranslatorInterface;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;

class PetitionTable extends I18nModel
{
    protected $petitionTableGateway;
    protected $petitionAnswerTable;
    protected $petitionVoteTable;

    public function __construct(
        TranslatorInterface      $translator,
        TableGateway             $petitionTableGateway,
        PetitionMailingListTable $petitionMailingListTable,
        PetitionSignatureTable   $petitionSignatureTable,
        PetitionStatusTable      $petitionStatusTable)
    {
        parent::__construct($translator);

        $this->petitionTableGateway     = $petitionTableGateway;
        $this->petitionMailingListTable = $petitionMailingListTable;
        $this->petitionSignatureTable   = $petitionSignatureTable;
        $this->petitionStatusTable      = $petitionStatusTable;
    }

    protected function getPetitionsFromSelect(array $params, Select $select)
    {
        // Always order by lastModified
        $select
            ->order('p.lastModified DESC')
            ->where('latestStatus IS NOT NULL');

        // Check id
        $id = isset($params['id']) ? (int)$params['id'] : 0;
        if ($id) {
            $select->where(array('p.id' => $id));
        }

        // Check special id
        if (isset($params['id']) && $params['id'] == 'latest') {
            $params['limit'] = '1';
            $select
                ->where->lessThanOrEqualTo('p.lastModified', new \Zend\Db\Sql\Expression('CURRENT_TIMESTAMP'));
        }

        // Check limit
        $limit = isset($params['limit']) ? (int)$params['limit'] : 0;
        if ($limit) {
            $select->limit($limit);
        }

        // Check offset
        $offset = isset($params['offset']) ? (int)$params['offset'] : 0;
        if ($offset) {
            $select->offset($offset);
        }

        $row = $this->petitionTableGateway->selectWith($select);

        if ($row === null) {
            return array();
        }

        $results = array();
        foreach ($row as $result) {
            array_push($results, $result);
        }

        return $results;
    }

    public function getPetitions(array $params = array())
    {
        // Select the latest non NULL status available for each petition
        $statusSelect = new Select();
        $statusSelect
            ->from(array('t1' => 'PetitionStatus'))
            ->columns(array(
                'pid',
                'latestStatus' => 'content',
                'latestStatusDate' => 'date'
            ))
            ->join(
                array('t2' => 'PetitionStatus'),
                '(t1.pid = t2.pid AND t1.date < t2.date) OR (t1.pid = t2.pid AND t1.date = t2.date AND t1.id < t2.id)',
                array(),
                $statusSelect::JOIN_LEFT
            )
            ->where(array(new \Zend\Db\Sql\Predicate\IsNull('t2.pid')));

        $columns = array('latestStatus', 'latestStatusDate');

        // Select the petition IDs
        $petitionSelect = new Select();
        $petitionSelect
            ->from(array('p' => 'Petition'))
            ->columns(array('id'))
            ->join(array('s' => $statusSelect), 'p.id = s.pid', $columns, $petitionSelect::JOIN_LEFT);

        array_push($columns, 'id');


        // Select the number of signature per petition
        $signatureSelect = new Select();
        $signatureSelect
            ->from(array('sig' => 'PetitionSignature'))
            ->columns(
                array('signatureCount' => new \Zend\Db\Sql\Expression('IFNULL(COUNT(sig.id),0)'))
            )
            ->group(array(
                'sig.pid',
                'p.latestStatus',
                'p.latestStatusDate',
                'p.id'
            ))
            ->join(array('p' => $petitionSelect), 'p.id = sig.pid', $columns, $signatureSelect::JOIN_RIGHT);

        array_push($columns, 'signatureCount');


        // Select the number of mailing list per petition
        $mailingListSelect = new Select();
        $mailingListSelect
            ->from(array('m' => 'PetitionMailingList'))
            ->columns(
                array('mailingListCount' => new \Zend\Db\Sql\Expression('IFNULL(COUNT(m.id),0)'))
            )
            ->group(array(
                'm.pid',
                'p.latestStatus',
                'p.latestStatusDate',
                'p.id'
            ))
            ->join(array('p' => $signatureSelect), 'p.id = m.pid', $columns, $mailingListSelect::JOIN_RIGHT);

        array_push($columns, 'mailingListCount');

        $select = new Select();
        $select
            ->from(array('p' => 'Petition'))
            ->columns(array('*'))
            ->join(array('p1' => $mailingListSelect), 'p.id = p1.id', $columns, $select::JOIN_RIGHT);

        $results = $this->getPetitionsFromSelect($params, $select);

        foreach ($results as $result) {

            if (array_key_exists('status', $params)) {
                // Retrieve statuses
                $result->statuses = $this->petitionStatusTable->getPetitionStatuses(array('pid' => $result->id));
                if (count($result->statuses) > 0) {
                    $result->latestStatus = $result->statuses[0]->content;
                    $result->latestStatusDate = $result->statuses[0]->date;
                }
            }

            if (array_key_exists('signature', $params)) {
                // Retrieve signatures
                $result->signatures = $this->petitionSignatureTable->getPetitionSignatures(array('pid' => $result->id));
                $result->signatureCount = count($result->signatures);
            }

            if (array_key_exists('mailingList', $params)) {
                // Retrieve mailing list subscriptions
                $result->mailingLists = $this->petitionMailingListTable->getPetitionMailingLists(array('pid' => $result->id));
                $result->mailingListCount = count($result->mailingLists);
            }
        }

        return $results;
    }

    public function deletePetition($id)
    {
        $this->tableGateway->delete(array('id' => (int) $id));
    }
}