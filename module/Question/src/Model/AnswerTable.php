<?php
namespace Question\Model;

use RuntimeException;
use Zend\Db\TableGateway\TableGatewayInterface;

class AnswerTable
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

    public function getAnswers($qid)
    {
        $qid = (int) $qid;
        return $this->tableGateway->select(['qid' => $qid]);
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