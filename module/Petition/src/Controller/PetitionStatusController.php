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
use Zend\Session\Container;

class PetitionStatusController extends AbstractActionController
{
    protected $petitionStatusTable;

    public function __construct(PetitionStatusTable $table)
    {
        $this->petitionStatusTable = $table;
    }
}
