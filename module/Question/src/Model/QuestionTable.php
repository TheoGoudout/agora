<?php

namespace Question\Model;

use RuntimeException;
use Zend\Db\TableGateway\TableGatewayInterface;

class QuestionTable
{
    private $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        return $this->tableGateway->select();
    }

    public function getQuestion($id)
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

    public function saveQuestion(Question $question)
    {
        $data = [
            'title'  => $question->title,
        ];

        $id = (int) $question->id;

        if ($id === 0) {
            $this->tableGateway->insert($data);
            return;
        }

        if (! $this->getQuestion($id)) {
            throw new RuntimeException(sprintf(
                'Cannot update question with identifier %d; does not exist',
                $id
            ));
        }

        $this->tableGateway->update($data, ['id' => $id]);
    }

    public function deleteQuestion($id)
    {
        $this->tableGateway->delete(['id' => (int) $id]);
    }
}