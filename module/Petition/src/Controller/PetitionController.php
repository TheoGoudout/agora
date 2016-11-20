<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Petition\Controller;

use I18n\Controller\AbstractI18nActionController;
use Zend\I18n\Translator\TranslatorInterface;

use Zend\View\Model\ViewModel;
use Zend\Session\Container;

use Petition\Model\PetitionTable;
use Petition\Form\PetitionSignatureForm;
use Petition\Form\PetitionMailingListForm;

class PetitionController extends AbstractI18nActionController
{
    protected $petitionTable;

    public function __construct(
        TranslatorInterface $translator,
        PetitionTable       $table)
    {
        parent::__construct($translator);

        $this->petitionTable = $table;
    }

    public function indexAction()
    {
        // Get petition informations
        $pid = $this->params()->fromRoute('pid', 0);
        $petition = $this->petitionTable->getPetitions(array('id' => $pid));

        if (count($petition) !== 1) {
            $petition = null;
        } else {
            $petition = $petition[0];
        }

        // Get signature form
        $session = new Container('form');
        $signatureForm = $session->offsetGet('signatureForm');
        $session->offsetUnset('signatureForm');
        if (!$signatureForm) {
            $signatureForm = new PetitionSignatureForm($this->translator);
            $signatureForm->get('submit')->setValue($this->tr('Je signe!'));
            $signatureForm->get('pid')->setValue($pid);
        }

        // Get signature form
        $session = new Container('form');
        $mailingListForm = $session->offsetGet('mailingListForm');
        $session->offsetUnset('mailingListForm');
        if (!$mailingListForm) {
            $mailingListForm = new PetitionMailingListForm($this->translator);
            $mailingListForm->get('submit')->setValue($this->tr('Je m\'inscris à la mailing list!'));
            $mailingListForm->get('pid')->setValue($pid);
        }

        return new ViewModel(array(
            'petition'        => $petition,
            'signatureForm'   => $signatureForm,
            'mailingListForm' => $mailingListForm,
        ));
    }
}
