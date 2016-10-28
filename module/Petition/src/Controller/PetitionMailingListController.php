<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Petition\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
// use Zend\Session\Container;

use Petition\Model\PetitionMailingList;
use Petition\Model\PetitionMailingListTable;
use Petition\Form\PetitionMailingListForm;

class PetitionMailingListController extends AbstractActionController
{
    protected $petitionMailingListTable;

    public function __construct(PetitionMailingListTable $table)
    {
        $this->petitionMailingListTable = $table;
    }

    public function addAction ()
    {
        $form = new PetitionMailingListForm();

        $request = $this->getRequest();
        if ($request->isPost()) {
            $mailingList = new PetitionMailingList();
            $form->setInputFilter($mailingList->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $mailingList->exchangeArray($form->getData());
                $this->petitionMailingListTable->saveMailingList($mailingList);
            } else {
                session_start();
                $_SESSION['mailingListForm'] = $form;
                // $session = new Container('form');
                // $session->offsetSet('mailingListForm', $form);
            }
        }

        $pid = (int)$this->params()->fromRoute('pid', 0);
        return $this->redirect()->toRoute('petition', ['action' => 'index', 'pid' => $pid], [], true);
    }
}
