<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Poll\Model;

use I18n\Model\I18nModel;
use Zend\I18n\Translator\TranslatorInterface;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;

class PollTable extends I18nModel
{
    protected $pollTableGateway;
    protected $pollAnswerTable;
    protected $pollVoteTable;

    public function __construct(
        TranslatorInterface $translator,
        TableGateway        $pollTableGateway,
        PollAnswerTable     $pollAnswerTable,
        PollVoteTable       $pollVoteTable)
    {
        parent::__construct($translator);

        $this->pollTableGateway = $pollTableGateway;
        $this->pollAnswerTable  = $pollAnswerTable;
        $this->pollVoteTable    = $pollVoteTable;
    }

    protected function getPollsFromSelect($params, Select $select)
    {
        // Order by startDate by default
        $order = isset($params['order']) ? (string)$params['order'] : 'p.startDate DESC';
        if ($order) {
            $select->order($order);
        }

        // Check id
        $id = isset($params['id']) ? (int)$params['id'] : 0;
        if ($id) {
            $select->where(array('p.id' => $id));
        }

        // Check special id
        if (isset($params['id']) && $params['id'] == 'latest') {
            $select
                ->limit(1)
                ->where
                    ->lessThanOrEqualTo('p.startDate', new \Zend\Db\Sql\Expression('CURRENT_TIMESTAMP'));
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
        // Get vote count
        $subsubselect = new Select();
        $subsubselect
            ->from(array('v' => 'PollVote'))
            ->columns(
                array('pid', 'voteCount' => new \Zend\Db\Sql\Expression('IFNULL(COUNT(v.id),0)'))
            )
            ->group(array('pid'));

        // Get answer count
        $subselect = new Select();
        $subselect
            ->from(array('a' => 'PollAnswer'))
            ->columns(
                array('pid', 'answerCount' => new \Zend\Db\Sql\Expression('IFNULL(COUNT(a.id),0)'))
            )
            ->group(array(
                'voteCount',
                'pid',
            ))
            ->join(array('v1' => $subsubselect), 'v1.pid = a.pid', array('voteCount'), $subselect::JOIN_LEFT);

        // Get Poll
        $select = new Select();
        $select
            ->from(array('p' => 'Poll'))
            ->columns(array('*'))
            ->join(array('a1' => $subselect), 'a1.pid = p.id', array('voteCount', 'answerCount'), $select::JOIN_LEFT);


        $results = $this->getPollsFromSelect($params, $select);


        foreach ($results as $result) {
            if (isset($params['answers']) && $params['answers']) {
                // Retrieve answers
                $answerParams = array(
                    'pid' => $result->id,
                    'votes' => $params['votes'],
                );
                if (is_array($params['answers'])) {
                    $answerParams = array_merge($answerParams, $params['answers']);
                }
                $result->answers = $this->pollAnswerTable->getPollAnswers($answerParams);
            }

            if (isset($params['votes']) && $params['votes']) {
                // Retrieve votes
                $voteParams = array(
                    'pid' => $result->id,
                );
                if (is_array($params['votes'])) {
                    $voteParams = array_merge($voteParams, $params['votes']);
                }
                $result->votes = $this->pollVoteTable->getPollVotes($voteParams);
            }
        }

        return $results;
    }

    public function deletePoll($id)
    {
        $this->tableGateway->delete(array('id' => (int) $id));
    }
}