<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Poll\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;

class PollTable
{
    protected $pollTableGateway;
    protected $pollAnswerTable;
    protected $pollVoteTable;

    public function __construct(
        TableGateway    $pollTableGateway,
        PollAnswerTable $pollAnswerTable,
        PollVoteTable   $pollVoteTable)
    {
        $this->pollTableGateway = $pollTableGateway;
        $this->pollAnswerTable  = $pollAnswerTable;
        $this->pollVoteTable    = $pollVoteTable;
    }

    protected function getPollsFromSelect($params, Select $select)
    {
        // Always order by startDate
        $select->order('p.startDate DESC');

        // Check id
        $id = (int)$param['id'];
        if ($id) {
            $select->where(array('p.id' => (int)$param['id']));
        }

        // Check special id
        if ($param['id'] == 'latest') {
            $select
                ->limit(1)
                ->where->lessThanOrEqualTo('p.startDate', 'CURRENT_TIMESTAMP');
        }

        // Check limit
        $limit = (int)$param['limit'];
        if ($limit) {
            $select->limit($limit);
        }

        // Check offset
        $offset = (int)$param['offset'];
        if ($offset) {
            $select->offset($offset);
        }

        $row = $this->pollTableGateway->selectWith($select);

        if ($row === null) {
            return array();
        }

        $results = array();
        foreach ($row as $result) {
            array_push($results, $result);
        }

        return $results;
    }

    public function getPolls($params)
    {
        $subselect = null;
        $columns = array();

        if (!$params['votes']) {
            $subselect = new Select();
            $subselect
                ->from(array('v' => 'PollVote'))
                ->columns(
                    array('pid', 'voteCount' => new \Zend\Db\Sql\Expression('COUNT(v.id)'))
                )
                ->group(array_merge(
                    $columns,
                    array('pid')
                ));

            array_push($columns, 'voteCount');
        }

        if (!$params['answers']) {
            $subsubselect = null;
            if ($subselect !== null) {
                $subsubselect = $subselect;
            }

            $subselect = new Select();
            $subselect
                ->from(array('a' => 'PollAnswer'))
                ->columns(
                    array('pid', 'answerCount' => new \Zend\Db\Sql\Expression('COUNT(a.id)'))
                )
                ->group(array_merge(
                    $columns,
                    array('pid')
                ));

            if ($subsubselect !== null) {
                $subselect
                    ->join(array('v1' => $subsubselect), 'v1.pid = a.pid', $columns, $subselect::JOIN_LEFT);
            }

            array_push($columns, 'answerCount');
        }

        $select = new Select();
        $select
            ->from(array('p' => 'Poll'))
            ->columns(array('*'));

        if ($subselect !== null) {
            $select
                ->join(array('a1' => $subselect), 'a1.pid = p.id', $columns, $select::JOIN_LEFT);
        }

        $results = $this->getPollsFromSelect($params, $select);

        foreach ($results as $result) {
            if ($params['answers']) {
                // Retrieve answers
                $result->answers = $this->pollAnswerTable->getPollAnswersByPollId($result->id, !!$params['votes']);
                $result->answerCount = count($result->answers);
            }

            if ($params['votes']) {
                // Retrieve votes
                $result->votes = $this->pollVoteTable->getPollVotesByPollId($result->id);
                $result->voteCount = count($result->votes);
            }
        }

        return $results;
    }

    public function deletePoll($id)
    {
        $this->tableGateway->delete(array('id' => (int) $id));
    }
}