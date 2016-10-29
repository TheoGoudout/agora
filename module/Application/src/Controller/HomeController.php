<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Poll\Model\PollTable;
use Petition\Model\PetitionTable;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class HomeController extends AbstractActionController
{
    protected $pollTable;
    protected $petitionTable;

    public function __construct(
        PollTable     $pollTable,
        PetitionTable $petitionTable)
    {
        $this->pollTable     = $pollTable;
        $this->petitionTable = $petitionTable;
    }

    public function indexAction()
    {
        $latestPoll     = $this->pollTable->getPolls(array('id' => 'latest', 'answers' => true));
        $latestPetition = $this->petitionTable->getPetitions(array('id' => 'latest'));
        $oldPetitions   = $this->petitionTable->getPetitions(array('limit' => 5, 'offset' => 1));

        if (count($latestPetition) !== 1) {
            throw new \Exception("Could not find latest Petition");
        } else {
            $latestPetition = $latestPetition[0];
        }

        if (count($latestPoll) !== 1) {
            throw new \Exception("Could not find latest Poll");
        } else {
            $latestPoll = $latestPoll[0];
        }

        return new ViewModel(array(
            'latestPoll'     => $latestPoll,
            'latestPetition' => $latestPetition,
            'oldPetitions'   => $oldPetitions,
        ));
    }

    public function contactAction()
    {
        return new ViewModel();
    }

    public function aboutAction()
    {
        return new ViewModel();
    }
}