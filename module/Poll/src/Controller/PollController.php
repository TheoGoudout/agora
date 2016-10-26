<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Poll\Controller;

use Poll\Model\PollTable;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class PollController extends AbstractActionController
{
    protected $pollTable;

    public function __construct(PollTable $table)
    {
        $this->pollTable = $table;
    }

    public function indexAction()
    {
        $pid = $this->params()->fromRoute('pid', 0);
        $poll = $this->pollTable->getPolls(array('id' => $pid, 'answers' => true));

        if (count($poll) !== 1) {
            throw new \Exception("Could not find latest Poll");
        } else {
            $poll = $poll[0];
        }

        return new ViewModel(array(
            'poll' => $poll,
        ));
    }
}
