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

class PetitionMailingListTable
{
    protected $petitionMailingListTableGateway;

    public function __construct(TableGateway $petitionMailingListTableGateway)
    {
        $this->petitionMailingListTableGateway = $petitionMailingListTableGateway;
    }

    public function getPetitionMailingListsByPetitionId($pid)
    {
        $pid  = (int) $pid;
        $select = new Select();
        $select
            ->from(array('m' => 'PetitionMailingList'))
            ->columns(array('*'))
            ->where(array('pid' => $pid))
            ->order(array('creationDate DESC'));

        $results = array();
        foreach ($this->petitionMailingListTableGateway->selectWith($select) as $result) {
            array_push($results, $result);
        }

        return $results;
    }

    public function deletePetitionMailingList($id)
    {
        $this->tableGateway->delete(array('id' => (int) $id));
    }
}