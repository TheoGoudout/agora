<?php

namespace Answer\Model;

use RuntimeException;
use Zend\Db\TableGateway\TableGatewayInterface;

class AnswerTable
{
    private $tableGateway;
    private $questionTable;

    public function __construct(
        TableGatewayInterface $tableGateway,
        \Question\Model\QuestionTable $questionTable)
    {
        $this->tableGateway = $tableGateway;
        $this->questionTable  = $questionTable;
    }

    public function fetchQuestion($qid)
    {
        return $this->questionTable->getQuestion($qid);
    } 

    public function fetchQuestionAnswers($qid)
    {
        return [
            'question' => $this->questionTable->getQuestion($qid),
            'answers'  => $this->tableGateway->select(),
        ];
    }

    public function getAnswer($id)
    {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(['id' => $id]);
        $row = $rowset->current();
        if (! $row) {
            throw new RuntimeException(sprintf(
                'Could not find row with identifier %d',
                $id
            ));
        }

        return $row;
    }

    public function saveAnswer(Answer $answer)
    {
        $data = [
            'qid'    => $answer->qid,
            'title'  => $answer->title,
        ];

        $id = (int) $answer->id;

        if ($id === 0) {
            $this->tableGateway->insert($data);
            return;
        }

        if (! $this->getAnswer($id)) {
            throw new RuntimeException(sprintf(
                'Cannot update answer with identifier %d; does not exist',
                $id
            ));
        }

        $this->tableGateway->update($data, ['id' => $id]);
    }

    public function deleteAnswer($id)
    {
        $this->tableGateway->delete(['id' => (int) $id]);
    }
}