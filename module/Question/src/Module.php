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
                    $tableGateway = $container->get(Model\QuestionTableGateway::class);
                    return new Model\QuestionTable($tableGateway);
                },
                Model\QuestionTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Question());
                    return new TableGateway('question', $dbAdapter, null, $resultSetPrototype);
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
                        $container->get(Model\QuestionTable::class)
                    );
                },
            ],
        ];
    }
}