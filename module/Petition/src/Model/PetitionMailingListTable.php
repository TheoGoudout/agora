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
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Update;

class PetitionMailingListTable extends I18nModel
{
    protected $petitionMailingListTableGateway;

    public function __construct(
        TranslatorInterface $translator,
        TableGateway        $petitionMailingListTableGateway)
    {
        parent::__construct($translator);

        $this->petitionMailingListTableGateway = $petitionMailingListTableGateway;
    }

    public function getPetitionMailingLists($params)
    {
        $select = new Select();
        $select
            ->from(array('m' => 'PetitionMailingList'))
            ->columns(array('*'));

        // Always order by date
        $select->order('m.lastModified DESC');

        // Check id
        $id = isset($params['id']) ? (int)$params['id'] : 0;
        if ($id) {
            $select->where(array('m.id' => $id));
        }

        // Check special id
        if (isset($params['id']) && $params['id'] == 'latest') {
            $select
                ->limit(1)
                ->where->lessThanOrEqualTo('m.lastModified', 'CURRENT_TIMESTAMP');
        }

        // Check petition id
        $pid = isset($params['pid']) ? (int)$params['pid'] : 0;
        if ($pid) {
            $select->where(array('m.pid' => $pid));
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

        $row = $this->petitionMailingListTableGateway->selectWith($select);

        if ($row === null) {
            return array();
        }

        $results = array();
        foreach ($row as $result) {
            array_push($results, $result);
        }

        return $results;
    }

    public function saveMailingList(PetitionMailingList $mailingList)
    {
        $data = array(
            'pid'          => $mailingList->pid,
            'email'        => $mailingList->email,
            'enabled'      => true,
        );

        $id = (int) $mailingList->id;
        if ($id == 0) {
            $this->petitionMailingListTableGateway->insert($data);
        } else if ($this->getPetitionMailingLists(array('id' => $id))) {
            $this->tableGateway->update($data, array('id' => $id));
        } else {
            throw new \Exception($this->tr('PetitionMailingList id $id n\'existe pas'));
        }
    }

    public function deletePetitionMailingList($id)
    {
        $this->tableGateway->delete(array('id' => (int) $id));
    }
}