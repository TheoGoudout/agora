<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Petition\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Petition\Model\PetitionSignature;
use Petition\Model\PetitionSignatureTable;
use Petition\Form\PetitionSignatureForm;

class PetitionSignatureController extends AbstractActionController
{
    protected $petitionSignatureTable;

    public function __construct(PetitionSignatureTable $table)
    {
        $this->petitionSignatureTable = $table;
    }

    public function addAction ()
    {
        $form = new PetitionSignatureForm();

        $request = $this->getRequest();
        if ($request->isPost()) {
            $signature = new PetitionSignature();
            $form->setInputFilter($signature->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $signature->exchangeArray($form->getData());
                $this->petitionSignatureTable->saveSignature($signature);
            } else {
                var_dump($form->getMessages());
                return;
            }
        }

        $pid = (int)$this->params()->fromRoute('pid', 0);
        return $this->redirect()->toRoute('petition', ['action' => 'index', 'pid' => $pid], [], true);
    }
}
