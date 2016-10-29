<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Petition\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Update;

class PetitionStatusTable
{
    protected $petitionStatusTableGateway;

    public function __construct(TableGateway $petitionStatusTableGateway)
    {
        $this->petitionStatusTableGateway = $petitionStatusTableGateway;
    }

    public function getPetitionStatuses($params)
    {
        $select = new Select();
        $select
            ->from(array('s' => 'PetitionStatus'))
            ->columns(array('*'));

        // Always order by date
        $select->order('s.date DESC');

        // Check id
        $id = isset($params['id']) ? (int)$params['id'] : 0;
        if ($id) {
            $select->where(array('s.id' => $id));
        }

        // Check special id
        if (isset($params['id']) && $params['id'] == 'latest') {
            $select
                ->limit(1)
                ->where->lessThanOrEqualTo('s.date', 'CURRENT_TIMESTAMP');
        }

        // Check petition id
        $pid = isset($params['pid']) ? (int)$params['pid'] : 0;
        if ($pid) {
            $select->where(array('s.pid' => $pid));
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

        $row = $this->petitionStatusTableGateway->selectWith($select);

        if ($row === null) {
            return array();
        }

        $results = array();
        foreach ($row as $result) {
            array_push($results, $result);
        }

        return $results;
    }

    public function deletePetitionStatus($id)
    {
        $this->tableGateway->delete(array('id' => (int) $id));
    }
}