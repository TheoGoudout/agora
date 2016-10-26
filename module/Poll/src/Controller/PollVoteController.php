<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Poll\Controller;

use Poll\Model\PollVoteTable;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class PollVoteController extends AbstractActionController
{
    protected $pollVoteTable;

    public function __construct(PollVoteTable $table)
    {
        $this->pollVoteTable = $table;
    }

    public function voteAction()
    {
        // Get vote informations
        $pid = (int)$this->params()->fromRoute('pid', 0);
        $aid = (int)$this->params()->fromRoute('aid', 0);
        $ipAddress = $_SERVER['REMOTE_ADDR'];

        // Add/update vote for given IP and update validation value
        $success = $this->pollVoteTable->setPollVote($pid, $aid, $ipAddress);

        if ($success == true) {
            $result = new ViewModel(array(
                'vote' => $this->pollVoteTable->getPollVoteByPollIdAndIpAddress($pid, $ipAddress),
            ));
            $result->setTerminal(true);
            $result->setVariables(array('items' => 'items'));
            return $result;
        } else {
            return new ViewModel();
        }


        // Redirect to vote confirmation
//        return $this->redirect()->toRoute('vote', ['action' => 'confirmVote', 'param' => $row->validationValue], [], true);
    }

    public function confirmVoteAction()
    {
        // Get vote informations
        $pid             = (int)$this->params()->fromRoute('pid', 0);
        $aid             = (int)$this->params()->fromRoute('aid', 0);
        $validationValue = (int)$this->params()->fromRoute('param', 0);
        $ipAddress = $_SERVER['REMOTE_ADDR'];

        // Add/update vote for given IP and update validation value
        $row = $this->pollVoteTable->validatePollVote($pid, $aid, $ipAddress, $validationValue);

        // Redirect to vote confirmation
        return $this->redirect()->toRoute('poll', ['action' => 'index', 'pid' => $pid], [], true);

    }
}
