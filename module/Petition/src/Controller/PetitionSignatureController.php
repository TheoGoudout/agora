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

use Petition\Model\PetitionSignature;
use Petition\Model\PetitionSignatureTable;
use Petition\Form\PetitionSignatureForm;

class PetitionSignatureController extends AbstractI18nActionController
{
    protected $petitionSignatureTable;

    public function __construct(
        TranslatorInterface    $translator,
        PetitionSignatureTable $table)
    {
        parent::__construct($translator);

        $this->petitionSignatureTable = $table;
    }

    public function addAction ()
    {
        $form = new PetitionSignatureForm($this->translator);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $signature = new PetitionSignature($this->translator);
            $form->setInputFilter($signature->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $signature->exchangeArray($form->getData());
                $this->petitionSignatureTable->saveSignature($signature);
            } else {
                $session = new Container('form');
                $session->offsetSet('signatureForm', $form);
            }
        }

        $pid = (int)$this->params()->fromRoute('pid', 0);
        return $this->redirect()->toRoute('petition', ['action' => 'index', 'pid' => $pid], [], true);
    }
}
