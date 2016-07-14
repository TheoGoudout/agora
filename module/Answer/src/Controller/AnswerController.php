<?php

namespace Answer\Controller;

use Answer\Form\AnswerForm;
use Answer\Model\Answer;
use Answer\Model\AnswerTable;

use Zend\I18n\Translator\Translator;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class AnswerController extends AbstractActionController
{
    private $_table;
    private $_translator;

    public function __construct(AnswerTable $table, Translator $translator)
    {
        $this->_table = $table;
        $this->_translator = $translator;
    }

    protected function table()
    {
        return $this->_table;
    }

    protected function translate(
        string $message,
        string $textdomain = null,
        string $locale = null)
    {
        return $this->_translator->translate($message, $textdomain, $locale);
    }

    protected function locale()
    {
        return $this->_translator->getLocale();
    }

    protected function redirectToRoute(
        string $route,
        array $params = [],
        array $options = [],
        bool $reuseMatchedParams = false)
    {
        $params['lang'] = $this->locale();

        return $this->redirect()->toRoute($route, $params, $options, $reuseMatchedParams);
    }

    public function indexAction()
    {
        $qid = (int) $this->params()->fromRoute('qid', 0);

        if (0 === $qid) {
            return $this->redirectToRoute('question');
        }

        try {
            return $this->table()->fetchQuestionAnswers($qid);
        } catch (\Exception $e) {
            return $this->redirectToRoute('question');
        }
    }

    public function addAction()
    {
        $qid = (int) $this->params()->fromRoute('qid', 0);

        if (0 === $qid) {
            return $this->redirectToRoute('question');
        }

        // Retrieve the question with the specified id. Doing so raises
        // an exception if the question is not found, which should result
        // in redirecting to the landing page.
        try {
            $question = $this->table()->fetchQuestion($qid);   
        } catch (\Exception $e) {
            return $this->redirectToRoute('question');
        }

        $form = new AnswerForm();
        $form->get('submit')->setValue($this->translate('Add'));

        $request = $this->getRequest();

        if (! $request->isPost()) {
            return [
                'form'     => $form,
                'question' => $question,
            ];
        }

        $answer = new Answer();
        $form->setInputFilter($answer->getInputFilter());
        $form->setData($request->getPost());

        if (! $form->isValid()) {
            return [
                'form'     => $form,
                'question' => $question,
            ];
        }

        $answer->exchangeArray($form->getData());
        $this->table()->saveAnswer($answer);
        return $this->redirectToRoute('answer', ['action' => 'index', 'qid' => $qid]);
    }

    public function editAction()
    {
        $qid = (int) $this->params()->fromRoute('qid', 0);

        if (0 === $qid) {
            return $this->redirectToRoute('question');
        }

        // Retrieve the question with the specified id. Doing so raises
        // an exception if the question is not found, which should result
        // in redirecting to the landing page.
        try {
            $question = $this->table()->fetchQuestion($qid);   
        } catch (\Exception $e) {
            return $this->redirectToRoute('question');
        }


        $id = (int) $this->params()->fromRoute('id', 0);

        if (0 === $id) {
            return $this->redirectToRoute('answer', ['action' => 'index']);
        }

        // Retrieve the answer with the specified id. Doing so raises
        // an exception if the answer is not found, which should result
        // in redirecting to the landing page.
        try {
            $answer = $this->table()->getAnswer($id);   
        } catch (\Exception $e) {
            return $this->redirectToRoute('answer', ['action' => 'add']);
        }

        $form = new AnswerForm();
        $form->bind($answer);
        $form->get('submit')->setAttribute('value', $this->translate('Edit'));

        $request = $this->getRequest();
        $viewData = ['question' => $question, 'answer' => $answer, 'form' => $form];

        if (! $request->isPost()) {
            return $viewData;
        }

        $form->setInputFilter($answer->getInputFilter());
        $form->setData($request->getPost());

        if (! $form->isValid()) {
            return $viewData;
        }

        $this->table()->saveAnswer($answer);

        // Redirect to answer list
        return $this->redirectToRoute('answer', ['qid' => $qid]);
    }

    public function deleteAction()
    {
        $qid = (int) $this->params()->fromRoute('qid', 0);

        if (0 === $qid) {
            return $this->redirectToRoute('question');
        }

        // Retrieve the question with the specified id. Doing so raises
        // an exception if the question is not found, which should result
        // in redirecting to the landing page.
        try {
            $question = $this->table()->fetchQuestion($qid);   
        } catch (\Exception $e) {
            return $this->redirectToRoute('question');
        }


        $id = (int) $this->params()->fromRoute('id', 0);

        if (0 === $id) {
            return $this->redirectToRoute('answer', ['action' => 'index']);
        }

        // Retrieve the answer with the specified id. Doing so raises
        // an exception if the answer is not found, which should result
        // in redirecting to the landing page.
        try {
            $answer = $this->table()->getAnswer($id);   
        } catch (\Exception $e) {
            return $this->redirectToRoute('answer', ['action' => 'add']);
        }


        $request = $this->getRequest();
        $viewData = ['question' => $question, 'answer' => $answer, 'form' => $form];

        if (! $request->isPost()) {
            return $viewData;
        }

        $del = $request->getPost('del', 'no');

        if ($del == 'yes') {
            $id = (int) $request->getPost('id');
            $this->table()->deleteAnswer($id);
        }

        // Redirect to list of questions
        return $this->redirectToRoute('answer', ['qid' => $qid]);
    }
}