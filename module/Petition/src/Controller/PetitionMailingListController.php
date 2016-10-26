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

class PetitionMailingListController extends AbstractActionController
{
    protected $petitionMailingListTable;

    public function __construct(PetitionMailingListTable $table)
    {
        $this->petitionMailingListTable = $table;
    }

    public function subscribe ()
    {
        $email = $this->params()->fromRoute('', 0);
    }

    public function unsubscribe ()
    {
        $email = $this->params()->fromRoute('', 0);
    }
}
