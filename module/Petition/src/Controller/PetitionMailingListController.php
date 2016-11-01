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
// use Zend\Session\Container;

use Petition\Model\PetitionMailingList;
use Petition\Model\PetitionMailingListTable;
use Petition\Form\PetitionMailingListForm;

class PetitionMailingListController extends AbstractI18nActionController
{
    protected $petitionMailingListTable;

    public function __construct(
        TranslatorInterface      $translator,
        PetitionMailingListTable $table)
    {
        parent::__construct($translator);

        $this->petitionMailingListTable = $table;
    }

    public function addAction ()
    {
        $form = new PetitionMailingListForm($this->translator);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $mailingList = new PetitionMailingList($this->translator);
            $form->setInputFilter($mailingList->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $mailingList->exchangeArray($form->getData());
                $this->petitionMailingListTable->saveMailingList($mailingList);
            } else {
                $session = new Container('form');
                $session->offsetSet('mailingListForm', $form);
            }
        }

        $pid = (int)$this->params()->fromRoute('pid', 0);
        return $this->redirect()->toRoute('petition', ['action' => 'index', 'pid' => $pid], [], true);
    }
}
