<?php

namespace Question\Controller;

use Question\Form\QuestionForm;
use Question\Model\Question;
use Question\Model\QuestionTable;

use Zend\I18n\Translator\Translator;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class QuestionController extends AbstractActionController
{
    private $_table;
    private $_translator;

    public function __construct(QuestionTable $table, Translator $translator)
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
        return new ViewModel([
            'questions' => $this->table()->fetchAll(),
        ]);
    }

    public function addAnswerAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        if (0 === $id) {
            return $this->redirectToRoute('question');
        }

        try {
            $question = $this->table()->getQuestion($id);
        } catch (\Exception $e) {
            return $this->redirectToRoute('question');
        }

        return new ViewModel([
            'answers'  => $this->table()->fetchAnswers($id),
            'question' => $question,
        ]);

    }

    public function addAction()
    {
        $form = new QuestionForm();
        $form->get('submit')->setValue($this->translate('Add'));

        $request = $this->getRequest();

        if (! $request->isPost()) {
            return ['form' => $form];
        }

        $question = new Question();
        $form->setInputFilter($question->getInputFilter());
        $form->setData($request->getPost());

        if (! $form->isValid()) {
            return ['form' => $form];
        }

        $question->exchangeArray($form->getData());
        $this->table()->saveQuestion($question);
        return $this->redirectToRoute('question');
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        if (0 === $id) {
            return $this->redirectToRoute('question', ['action' => 'add']);
        }

        // Retrieve the question with the specified id. Doing so raises
        // an exception if the question is not found, which should result
        // in redirecting to the landing page.
        try {
            $question = $this->table()->getQuestion($id);
        } catch (\Exception $e) {
            return $this->redirectToRoute('question');
        }

        $form = new QuestionForm();
        $form->bind($question);
        $form->get('submit')->setAttribute('value', $this->translate('Edit'));

        $request = $this->getRequest();
        $viewData = ['id' => $id, 'form' => $form];

        if (! $request->isPost()) {
            return $viewData;
        }

        $form->setInputFilter($question->getInputFilter());
        $form->setData($request->getPost());

        if (! $form->isValid()) {
            return $viewData;
        }

        $this->table()->saveQuestion($question);

        // Redirect to question list
        return $this->redirectToRoute('question');
    }

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirectToRoute('question');
        }

        $request = $this->getRequest();

        if (! $request->isPost()) {
            return array(
                'id'    => $id,
                'question' => $this->table()->getQuestion($id)
            );
        }

        $del = $request->getPost('del', 'no');

        if ($del == 'yes') {
            $id = (int) $request->getPost('id');
            $this->table()->deleteQuestion($id);
        }

        // Redirect to list of questions
        return $this->redirectToRoute('question');
    }
}