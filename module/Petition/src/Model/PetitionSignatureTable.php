<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Petition\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Update;

use Petition\Model\PetitionSignature;

class PetitionSignatureTable
{
    protected $petitionSignatureTableGateway;

    public function __construct(TableGateway $petitionSignatureTableGateway)
    {
        $this->petitionSignatureTableGateway = $petitionSignatureTableGateway;
    }

    public function getPetitionSignatureById($id)
    {
        $id  = (int) $id;
        $rowset = $this->petitionSignatureTableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function getPetitionSignaturesByPetitionId($pid)
    {
        $pid  = (int) $pid;
        $select = new Select();
        $select
            ->from(array('s' => 'PetitionSignature'))
            ->columns(array('*'))
            ->where(array('pid' => $pid))
            ->order(array('creationDate DESC'));

        $results = array();
        foreach ($this->petitionSignatureTableGateway->selectWith($select) as $result) {
            array_push($results, $result);
        }

        return $results;
    }

    public function saveSignature(PetitionSignature $signature)
    {
        $data = array(
            'pid'          => $signature->pid,
            'gender'       => $signature->gender,
            'firstName'    => $signature->firstName,
            'lastName'     => $signature->lastName,
            'address1'     => $signature->address1,
            'address2'     => $signature->address2,
            'address3'     => $signature->address3,
            'zipCode'      => $signature->zipCode,
            'city'         => $signature->city,
        );

        $id = (int) $signature->id;
        if ($id == 0) {
            $this->petitionSignatureTableGateway->insert($data);
        } else {
            if ($this->getPetitionSignatureById($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('PetitionSignature id does not exist');
            }
        }
    }

    public function deletePetitionSignature($id)
    {
        $this->tableGateway->delete(array('id' => (int) $id));
    }
}