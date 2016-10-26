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
        $id = isset($params['id']) ? (int)$params['id'] : 0;
        if ($id) {
            $select->where(array('p.id' => $id));
        }

        // Check special id
        if (isset($params['id']) && $params['id'] == 'latest') {
            $select
                ->limit(1)
                ->where->lessThanOrEqualTo('p.startDate', 'CURRENT_TIMESTAMP');
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

        if (!array_key_exists('votes', $params)) {
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

        if (!array_key_exists('answers', $params)) {
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
            if (array_key_exists('answers', $params)) {
                // Retrieve answers
                $result->answers = $this->pollAnswerTable->getPollAnswersByPollId($result->id, array_key_exists('votes', $params));
                $result->answerCount = count($result->answers);
            }

            if (array_key_exists('votes', $params)) {
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