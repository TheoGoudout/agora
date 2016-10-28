<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Petition\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;

use Petition\Model\PetitionTable;
use Petition\Form\PetitionSignatureForm;
use Petition\Form\PetitionMailingListForm;

class PetitionController extends AbstractActionController
{
    protected $petitionTable;

    public function __construct(PetitionTable $table)
    {
        $this->petitionTable = $table;
    }

    public function indexAction()
    {
        // Get petition informations
        $pid = $this->params()->fromRoute('pid', 0);
        $petition = $this->petitionTable->getPetitions(array('id' => $pid));

        if (count($petition) !== 1) {
            throw new \Exception("Could not find Petition with ID : $pid");
        } else {
            $petition = $petition[0];
        }

        // Get signature form
        session_start();
        $signatureForm = $_SESSION['signatureForm'];
        unset($_SESSION['signatureForm']);
        // $session = new Container('form');
        // $lastForm = $session->offsetGet('signatureForm');
        // $session->offsetUnset('lastForm');
        if (!$signatureForm) {
            $signatureForm = new PetitionSignatureForm();
            $signatureForm->get('submit')->setValue('Je signe!');
            $signatureForm->get('pid')->setValue($pid);
        }

        // Get signature form
        session_start();
        $mailingListForm = $_SESSION['mailingListForm'];
        unset($_SESSION['mailingListForm']);
        // $session = new Container('form');
        // $lastForm = $session->offsetGet('mailingListForm');
        // $session->offsetUnset('lastForm');
        if (!$mailingListForm) {
            $mailingListForm = new PetitionMailingListForm();
            $mailingListForm->get('submit')->setValue('Je m\'inscris à la mailing list!');
            $mailingListForm->get('pid')->setValue($pid);
        }

        return new ViewModel(array(
            'petition'        => $petition,
            'signatureForm'   => $signatureForm,
            'mailingListForm' => $mailingListForm,
        ));
    }
}
