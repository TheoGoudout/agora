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

    public function getPollVoteByPollIdAndIpAddress($pid, $ipAddress)
    {
        $id  = (int) $id;
        $select = new Select();
        $select
            ->from(array('v' => 'PollVote'))
            ->columns(array('*'))
            ->where(array(
                'pid'       => $pid,
                'ipAddress' => $ipAddress
            ))
            ->order(array('creationDate DESC'));

        $result = $this->pollVoteTableGateway->selectWith($select)->current();
        if (!$result) {
            throw new \Exception("Could not find PollVote with PID : $pid and IP address : $ipAddress");
        }

        return $result;
    }

    public function getPollVotesByPollId($pid)
    {
        $pid  = (int) $pid;
        $select = new Select();
        $select
            ->from(array('v' => 'PollVote'))
            ->columns(array('*'))
            ->where(array('pid' => $pid))
            ->order(array('creationDate DESC'));

        $results = array();
        foreach ($this->pollVoteTableGateway->selectWith($select) as $result) {
            array_push($results, $result);
        }

        return $results;
    }

    public function getPollVotesByPollAnswerId($aid)
    {
        $aid  = (int) $aid;
        $select = new Select();
        $select
            ->from(array('v' => 'PollVote'))
            ->columns(array('*'))
            ->where(array('aid' => $aid))
            ->order(array('creationDate DESC'));

        $results = array();
        foreach ($this->pollVoteTableGateway->selectWith($select) as $result) {
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