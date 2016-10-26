<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Petition\Controller;

use Petition\Model\PetitionTable;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class PetitionsController extends AbstractActionController
{
    protected $petitionTable;

    public function __construct(PetitionTable $table)
    {
        $this->petitionTable = $table;
    }

    public function indexAction()
    {
        $pid = $this->params()->fromRoute('pid', 0);
        $row = $this->petitionTable->getPetitions(array('limit' => 10));

        return new ViewModel(array(
            'petitions' => $row,
        ));
    }
}
