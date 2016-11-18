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

class PollAnswerTable extends I18nModel
{
    protected $pollAnswerTableGateway;
    protected $pollVoteTable;

    public function __construct(
        TranslatorInterface $translator,
        TableGateway        $pollAnswerTableGateway,
        PollVoteTable       $pollVoteTable)
    {
        parent::__construct($translator);

        $this->pollAnswerTableGateway = $pollAnswerTableGateway;
        $this->pollVoteTable          = $pollVoteTable;
    }

    protected function getPollAnswersFromSelect($params, Select $select)
    {
        // Order by vote count by default
        $order = isset($params['order']) ? (string)$params['order'] : 'v1.voteCount DESC';
        if ($order) {
            $select->order($order);
        }

        // Check id
        $id = isset($params['id']) ? (int)$params['id'] : 0;
        if ($id) {
            $select->where(array('a.id' => $id));
        }

        // Check special id
        if (isset($params['id']) && $params['id'] == 'latest') {
            $select
                ->limit(1)
                ->where->lessThanOrEqualTo('a.lastModified', 'CURRENT_TIMESTAMP');
        }

        // Check limit
        $limit = isset($params['limit']) ? (int)$params['limit'] : 0;
        if ($limit) {
            $select->limit($limit);
        }

        // Check poll id
        $pid = isset($params['pid']) ? (int)$params['pid'] : 0;
        if ($pid) {
            $select->where(array('a.pid' => $pid));
        }

        // Check offset
        $offset = isset($params['offset']) ? (int)$params['offset'] : 0;
        if ($offset) {
            $select->offset($offset);
        }

        $row = $this->pollAnswerTableGateway->selectWith($select);

        if ($row === null) {
            return array();
        }

        $results = array();
        foreach ($row as $result) {
            array_push($results, $result);
        }

        return $results;
    }

    public function getPollAnswers($params)
    {
        $subselect = new Select();
        $subselect
            ->from(array('v' => 'PollVote'))
            ->columns(
                array('aid', 'voteCount' => new \Zend\Db\Sql\Expression('IFNULL(COUNT(v.id),0)'))
            )
            ->group(array('aid'));

        $select = new Select();
        $select
            ->from(array('a' => 'PollAnswer'))
            ->columns(array('*'));

        if ($subselect !== null) {
            $select
                ->join(array('v1' => $subselect), 'v1.aid = a.id', array('voteCount'), $select::JOIN_LEFT);
        }

        $results = $this->getPollAnswersFromSelect($params, $select);

        foreach ($results as $result) {
            if (isset($params['votes']) && $params['votes']) {
                // Retrieve votes
                $result->votes = $this->pollVoteTable->getPollVotes(array('aid' => $result->id));
                $result->voteCount = count($result->votes);
            }
        }

        return $results;
    }

    public function deletePollAnswer($id)
    {
        $this->tableGateway->delete(array('id' => (int) $id));
    }
}