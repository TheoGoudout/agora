<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Poll\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;

class PollAnswerTable
{
    protected $pollAnswerTableGateway;
    protected $pollVoteTable;

    public function __construct(
        TableGateway  $pollAnswerTableGateway,
        PollVoteTable $pollVoteTable)
    {
        $this->pollAnswerTableGateway = $pollAnswerTableGateway;
        $this->pollVoteTable          = $pollVoteTable;
    }

    public function getPollAnswerById($id, $votes = false)
    {
        $id  = (int) $id;

        $select = new Select();
        $select
            ->from(array('a' => 'PollAnswer'))
            ->columns(array('*'))
            ->where(array('a.id' => $id));

        if ($votes === false) {
            $select
                ->columns(array('*', 'voteCount' => new \Zend\Db\Sql\Expression('COUNT(v.id)')))
                ->join(array('v' => 'PollVote'), 'a.id = v.aid', array(), $select::JOIN_LEFT)
                ->where(array('v.validationStatus' => 1))
                ->group('a.id');
        }

        $result = $this->pollAnswerTableGateway->selectWith($select)->current();
        if ($votes === true) {
            $result->votes = $this->pollVoteTable->getPollVotesByPollAnswerId($result->id);
            $result->voteCount = count($result->votes);
        }

        return $result;
    }

    public function getPollAnswersByPollId($pid, $votes = false)
    {
        $pid  = (int) $pid;

        $select = new Select();
        $select
            ->from(array('a' => 'PollAnswer'))
            ->columns(array('*'))
            ->where(array('a.pid' => $pid));

        if ($votes === false) {
            $subselect = new Select();
            $subselect
                ->from(array('v1' => 'PollVote'))
                ->columns(array('*'))
                ->where(array('v1.validationStatus' => 1));

            $select
                ->columns(array('*', 'voteCount' => new \Zend\Db\Sql\Expression('COUNT(v.id)')))
                ->join(array('v' => $subselect), 'a.id = v.aid', array(), $select::JOIN_LEFT)
                ->group('a.id');
        }

        $results = array();
        foreach ($this->pollAnswerTableGateway->selectWith($select) as $result) {
            if ($votes === true) {
                $result->votes = $this->pollVoteTable->getPollVotesByPollAnswerId($result->id);
                $result->voteCount = count($result->votes);
            }

            array_push($results, $result);
        }

        return $results;
    }

    public function deletePollAnswer($id)
    {
        $this->tableGateway->delete(array('id' => (int) $id));
    }
}