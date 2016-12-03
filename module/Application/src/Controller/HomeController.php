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

use I18n\Controller\AbstractI18nActionController;
use Zend\I18n\Translator\TranslatorInterface;

class HomeController extends AbstractI18nActionController
{
    protected $pollTable;
    protected $petitionTable;

    public function __construct(
        TranslatorInterface $translator,
        PollTable           $pollTable,
        PetitionTable       $petitionTable)
    {
        parent::__construct($translator);

        $this->pollTable     = $pollTable;
        $this->petitionTable = $petitionTable;
    }

    public function indexAction()
    {
        $latestPoll = $this->pollTable->getPolls(array('id' => 'latest', 'answers' => array('limit' => 3)));
        $petitions  = $this->petitionTable->getPetitions(array('limit' => 4));

        $latestPetition = null;
        if (count($petitions) > 0) {
            $latestPetition = $petitions[0];
            array_shift($petitions);
        }

        if (count($latestPoll) !== 1) {
            $latestPoll = null;
        } else {
            $latestPoll = $latestPoll[0];
        }

        return new ViewModel(array(
            'latestPoll'     => $latestPoll,
            'latestPetition' => $latestPetition,
            'oldPetitions'   => $petitions,
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
