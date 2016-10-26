<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Petition\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;

class PetitionTable
{
    protected $petitionTableGateway;
    protected $petitionAnswerTable;
    protected $petitionVoteTable;

    public function __construct(
        TableGateway             $petitionTableGateway,
        PetitionMailingListTable $petitionMailingListTable,
        PetitionSignatureTable   $petitionSignatureTable,
        PetitionStatusTable      $petitionStatusTable)
    {
        $this->petitionTableGateway     = $petitionTableGateway;
        $this->petitionMailingListTable = $petitionMailingListTable;
        $this->petitionSignatureTable   = $petitionSignatureTable;
        $this->petitionStatusTable      = $petitionStatusTable;
    }

    protected function getPetitionsFromSelect(array $params, Select $select)
    {
        // Always order by creationDate
        $select->order('p.creationDate DESC');

        // Check id
        $id = (int)$params['id'];
        if ($id) {
            $select->where(array('p.id' => (int)$params['id']));
        }

        // Check special id
        if ($params['id'] == 'latest') {
            $select
                ->limit(1)
                ->where->lessThanOrEqualTo('p.creationDate', 'CURRENT_TIMESTAMP');
        }

        // Check limit
        $limit = (int)$params['limit'];
        if ($limit) {
            $select->limit($limit);
        }

        // Check offset
        $offset = (int)$params['offset'];
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
        $subselect = null;
        $columns = array();

        if (!$params['status']) {
            $subselect = new Select();
            $subselect
                ->from(array('t1' => 'PetitionStatus'))
                ->columns(array(
                    'pid',
                    'latestStatus' => 'content',
                    'latestStatusDate' => 'date'
                ))
                ->join(
                    array('t2' => 'PetitionStatus'),
                    '(t1.pid = t2.pid AND t1.date < t2.date) OR (t1.date = t2.date AND t1.id < t2.id)',
                    array(),
                    $subselect::JOIN_LEFT
                )
                ->where(array(new \Zend\Db\Sql\Predicate\IsNull('t2.pid')));

            $columns = array('latestStatus', 'latestStatusDate');
        }

        if (!$params['signature']) {
            $subsubselect = null;
            if ($subselect !== null) {
                $subsubselect = $subselect;
            }

            $subselect = new Select();
            $subselect
                ->from(array('sig' => 'PetitionSignature'))
                ->columns(
                    array('pid', 'signatureCount' => new \Zend\Db\Sql\Expression('COUNT(sig.id)'))
                )
                ->group(array_merge(
                    $columns,
                    array('pid')
                ));

            if ($subsubselect !== null) {
                $subselect
                    ->join(array('s1' => $subsubselect), 's1.pid = sig.pid', $columns, $subselect::JOIN_LEFT);
            }

            array_push($columns, 'signatureCount');
        }

        if (!$params['mailingList']) {
            $subsubselect = null;
            if ($subselect !== null) {
                $subsubselect = $subselect;
            }

            $subselect = new Select();
            $subselect
                ->from(array('m' => 'PetitionMailingList'))
                ->columns(
                    array('pid', 'mailingListCount' => new \Zend\Db\Sql\Expression('COUNT(m.id)'))
                )
                ->group(array_merge(
                    $columns,
                    array('pid')
                ));

            if ($subsubselect !== null) {
                $subselect
                    ->join(array('sig1' => $subsubselect), 'sig1.pid = m.pid', $columns, $subselect::JOIN_LEFT);
            }

            array_push($columns, 'mailingListCount');
        }

        $select = new Select();
        $select
            ->from(array('p' => 'Petition'))
            ->columns(array('*'));

        if ($subselect !== null) {
            $select
                ->join(array('m1' => $subselect), 'm1.pid = p.id', $columns, $select::JOIN_LEFT);
        }

        $results = $this->getPetitionsFromSelect($params, $select);

        foreach ($results as $result) {

            if ($params['status']) {
                // Retrieve statuses
                $result->statuses = $this->petitionStatusTable->getPetitionStatusesByPetitionId($result->id);
                if (count($result->statuses) > 0) {
                    $result->latestStatus = $result->statuses[0]->content;
                    $result->latestStatusDate = $result->statuses[0]->date;
                }
            }

            if ($params['signature']) {
                // Retrieve signatures
                $result->signatures = $this->petitionSignatureTable->getPetitionSignaturesByPetitionId($result->id);
                $result->signatureCount = count($result->signatures);
            }

            if ($params['mailingList']) {
                // Retrieve mailing list subscriptions
                $result->mailingLists = $this->petitionMailingListTable->getPetitionMailingListsByPetitionId($result->id);
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