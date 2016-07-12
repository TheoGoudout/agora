<?php

namespace Question;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ModuleManager\Feature\ConfigProviderInterface;

class Module implements ConfigProviderInterface
{
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function getServiceConfig()
    {
        return [
            'factories' => [
                Model\QuestionTable::class =>  function($container) {
                    return new Model\QuestionTable(
                        $container->get(Model\QuestionTableGateway::class),
                        $container->get(Model\AnswerTable::class)
                    );
                },
                Model\QuestionTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Question());
                    return new TableGateway('question', $dbAdapter, null, $resultSetPrototype);
                },

                Model\AnswerTable::class =>  function($container) {
                    return new Model\AnswerTable(
                        $container->get(Model\AnswerTableGateway::class)
                    );
                },
                Model\AnswerTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Answer());
                    return new TableGateway('answer', $dbAdapter, null, $resultSetPrototype);
                },
            ],
        ];
    }

    public function getControllerConfig()
    {
        return [
            'factories' => [
                Controller\QuestionController::class =>  function($container) {
                    return new Controller\QuestionController(
                        $container->get(Model\QuestionTable::class),
                        $container->get('Zend\I18n\Translator\TranslatorInterface')
                    );
                },
            ],
        ];
    }
}