<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Poll\Controller;

use I18n\Controller\AbstractI18nActionController;
use Zend\I18n\Translator\TranslatorInterface;

use Poll\Model\PollVoteTable;

use Zend\View\Model\ViewModel;

class PollVoteController extends AbstractI18nActionController
{
    protected $pollVoteTable;

    public function __construct(
        TranslatorInterface $Translator,
        PollVoteTable       $table)
    {
        parent::__construct($translator);

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

        if (!$success) {
            throw new \Exception('Unable to add PollVote into table');
        }

        $vote = $this->pollVoteTable->getPollVotes(array('pid' => $pid, 'ipAddress' => $ipAddress));

        if (count($vote) !== 1) {
            throw new \Exception("Could not find PollVote with PID : $pid an IP address $ipAddress");
        } else {
            $vote = $vote[0];
        }

        return new ViewModel(array(
            'vote' => $vote,
        ));

        $result->setTerminal(true);
        $result->setVariables(array('items' => 'items'));
        return $result;
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
