<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Petition\Model;

use I18n\Model\I18nModel;
use Zend\I18n\Translator\TranslatorInterface;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Update;

use Petition\Model\PetitionSignature;

class PetitionSignatureTable extends I18nModel
{
    protected $petitionSignatureTableGateway;

    public function __construct(
        TranslatorInterface $translator,
        TableGateway        $petitionSignatureTableGateway)
    {
        parent::__construct($translator);

        $this->petitionSignatureTableGateway = $petitionSignatureTableGateway;
    }

    public function getPetitionSignatures($params)
    {
        $select = new Select();
        $select
            ->from(array('s' => 'PetitionSignature'))
            ->columns(array('*'));

        // Always order by date
        $select->order('s.lastModified DESC');

        // Check id
        $id = isset($params['id']) ? (int)$params['id'] : 0;
        if ($id) {
            $select->where(array('s.id' => $id));
        }

        // Check special id
        if (isset($params['id']) && $params['id'] == 'latest') {
            $select
                ->limit(1)
                ->where->lessThanOrEqualTo('s.lastModified', 'CURRENT_TIMESTAMP');
        }

        // Check petition id
        $pid = isset($params['pid']) ? (int)$params['pid'] : 0;
        if ($pid) {
            $select->where(array('s.pid' => $pid));
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

        $row = $this->petitionSignatureTableGateway->selectWith($select);

        if ($row === null) {
            return array();
        }

        $results = array();
        foreach ($row as $result) {
            array_push($results, $result);
        }

        return $results;
    }

    public function getPetitionSignaturesByPetitionId($pid)
    {
        $pid  = (int) $pid;
        $select = new Select();
        $select
            ->from(array('s' => 'PetitionSignature'))
            ->columns(array('*'))
            ->where(array('pid' => $pid))
            ->order(array('lastModified DESC'));

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
        } else if ($this->getPetitionSignatures(array('id' => $id))) {
            $this->tableGateway->update($data, array('id' => $id));
        } else {
            throw new \Exception($this->tr('PetitionSignature id $id n\'existe pas'));
        }
    }

    public function deletePetitionSignature($id)
    {
        $this->tableGateway->delete(array('id' => (int) $id));
    }
}