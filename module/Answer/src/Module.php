<?php

namespace Answer;

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
                Model\AnswerTable::class =>  function($container) {
                    return new Model\AnswerTable(
                        $container->get(Model\AnswerTableGateway::class),
                        $container->get(\Question\Model\QuestionTable::class)
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
                Controller\AnswerController::class =>  function($container) {
                    return new Controller\AnswerController(
                        $container->get(Model\AnswerTable::class),
                        $container->get('Zend\I18n\Translator\TranslatorInterface')
                    );
                },
            ],
        ];
    }
}