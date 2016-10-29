<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Poll\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Update;

class PollVoteTable
{
    protected $pollVoteTableGateway;

    public function __construct(TableGateway $pollVoteTableGateway)
    {
        $this->pollVoteTableGateway = $pollVoteTableGateway;
    }

    public function getPollVotes($params)
    {
        $select = new Select();
        $select
            ->from(array('v' => 'PollVote'))
            ->columns(array('*'));

        // Order by creationDate by default
        $order = isset($params['order']) ? (string)$params['order'] : 'v.creationDate DESC';
        if ($order) {
            $select->order($order);
        }

        // Check id
        $id = isset($params['id']) ? (int)$params['id'] : 0;
        if ($id) {
            $select->where(array('v.id' => $id));
        }

        // Check special id
        if (isset($params['id']) && $params['id'] == 'latest') {
            $select
                ->limit(1)
                ->where->lessThanOrEqualTo('v.creationDate', 'CURRENT_TIMESTAMP');
        }

        // Check limit
        $limit = isset($params['limit']) ? (int)$params['limit'] : 0;
        if ($limit) {
            $select->limit($limit);
        }

        // Check poll id
        $pid = isset($params['pid']) ? (int)$params['pid'] : 0;
        if ($pid) {
            $select->where(array('v.pid' => $pid));
        }

        // Check answer id
        $aid = isset($params['aid']) ? (int)$params['aid'] : 0;
        if ($aid) {
            $select->where(array('v.aid' => $aid));
        }

        // Check ip address
        $ipAddress = isset($params['ipAddress']) ? (string)$params['ipAddress'] : '';
        if ($ipAddress) {
            $select->where(array('v.ipAddress' => $ipAddress));
        }

        // Check offset
        $offset = isset($params['offset']) ? (int)$params['offset'] : 0;
        if ($offset) {
            $select->offset($offset);
        }

        $row = $this->pollVoteTableGateway->selectWith($select);

        if ($row === null) {
            return array();
        }

        $results = array();
        foreach ($row as $result) {
            array_push($results, $result);
        }

        return $results;
    }

    public function setPollVote($pid, $aid, $ipAddress)
    {
        $pid  = (int) $pid;
        $aid  = (int) $aid;
        $select = new Select();
        $select
            ->from(array('v' => 'PollVote'))
            ->columns(array('*'))
            ->where(array('pid' => $pid, 'ipAddress' => $ipAddress))
            ->order(array('creationDate DESC'));

        $result = $this->pollVoteTableGateway->selectWith($select)->current();

        // No vote for this question yet -> insert it
        if ($result === null) {
            return $this->insertPollVote($pid, $aid, $ipAddress);
        }

        // Different vote for this question -> update it
        if ($result->aid != $aid) {
            return $this->updatePollVote($pid, $aid, $ipAddress);
        }

        // Vote not validated yet -> update validation value
        if ($result->validationStatus == false) {
            return $this->updatePollVote($pid, $aid, $ipAddress);
        }

        // Return selected poll vote
        return $result;
    }

    public function insertPollVote($pid, $aid, $ipAddress)
    {
        $pid  = (int) $pid;
        $aid  = (int) $aid;
        $insert = new Insert();
        $insert
            ->into('PollVote')
            ->values(array(
                'pid'             => $pid,
                'aid'             => $aid,
                'ipAddress'       => $ipAddress,
                'validationValue' => new \Zend\Db\Sql\Expression('FLOOR(RAND() * 1000000000)')
            ));

        return $this->pollVoteTableGateway->insertWith($insert);
    }

    public function updatePollVote($pid, $aid, $ipAddress)
    {
        $pid  = (int) $pid;
        $aid  = (int) $aid;
        $update = new Update();
        $update
            ->table('PollVote')
            ->set(array(
                'aid'             => $aid,
                'validationValue' => new \Zend\Db\Sql\Expression('FLOOR(RAND() * 1000000000)')
            ))
            ->where(array('pid' => $pid, 'ipAddress' => $ipAddress));

        return $this->pollVoteTableGateway->updateWith($update);
    }

    public function validatePollVote($pid, $aid, $ipAddress, $validationValue)
    {
        $pid  = (int) $pid;
        $aid  = (int) $aid;
        $select = new Select();
        $select
            ->from('PollVote')
            ->columns(array('*'))
            ->where(array('pid' => $pid, 'aid' => $aid, 'ipAddress' => $ipAddress))
            ->order(array('creationDate DESC'));

        $result = $this->pollVoteTableGateway->selectWith($select)->current();

        // No vote found -> cannot validate
        if ($result === null) {
            return false;
        }

        // Validation value is different -> change it
        if ($result->validationValue != $validationValue) {
            $this->updatePollVote($pid, $aid, $ipAddress);
            return false;
        }

        // Validate vote
        $update = new Update();
        $update
            ->table('PollVote')
            ->set(array('validationStatus' => 1))
            ->where(array('pid' => $pid, 'aid' => $aid, 'ipAddress' => $ipAddress, 'validationValue' => $validationValue));

        return $this->pollVoteTableGateway->updateWith($update);
    }

    public function deletePollVote($id)
    {
        $this->tableGateway->delete(array('id' => (int) $id));
    }
}