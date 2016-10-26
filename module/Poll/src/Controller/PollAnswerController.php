<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Poll\Controller;

use Poll\Model\PollAnswerTable;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class PollAnswerController extends AbstractActionController
{
    protected $pollAnswerTable;

    public function __construct(PollAnswerTable $table)
    {
        $this->pollAnswerTable = $table;
    }

    public function indexAction()
    {
        $aid = (int)$this->params()->fromRoute('aid', 0);
        $row = $this->pollAnswerTable->getPollAnswerById($aid, true);

        return new ViewModel(array(
            'answer' => $row,
        ));
    }
}
