<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Poll;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ModuleManager\Feature\ConfigProviderInterface;

class Module implements ConfigProviderInterface
{
    const VERSION = '3.0.0dev';

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function getControllerConfig()
    {
        return [
            'factories' => [
                Controller\PollController::class => function($container) {
                    return new Controller\PollController(
                        $container->get(Model\PollTable::class)
                    );
                },
                Controller\PollAnswerController::class => function($container) {
                    return new Controller\PollAnswerController(
                        $container->get(Model\PollAnswerTable::class)
                    );
                },
                Controller\PollVoteController::class => function($container) {
                    return new Controller\PollVoteController(
                        $container->get(Model\PollVoteTable::class)
                    );
                },
            ],
        ];
    }

    public function getServiceConfig()
    {
        return [
            'factories' => [
                Model\PollTable::class => function($container) {
                    return new Model\PollTable(
                        $container->get(Model\PollTableGateway::class),
                        $container->get(Model\PollAnswerTable::class),
                        $container->get(Model\PollVoteTable::class)
                    );
                },
                Model\PollAnswerTable::class => function($container) {
                    return new Model\PollAnswerTable(
                        $container->get(Model\PollAnswerTableGateway::class),
                        $container->get(Model\PollVoteTable::class)
                    );
                },
                Model\PollVoteTable::class => function($container) {
                    return new Model\PollVoteTable(
                        $container->get(Model\PollVoteTableGateway::class)
                    );
                },

                Model\PollTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Poll());
                    return new TableGateway('Poll', $dbAdapter, null, $resultSetPrototype);
                },
                Model\PollAnswerTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\PollAnswer());
                    return new TableGateway('PollAnswer', $dbAdapter, null, $resultSetPrototype);
                },
                Model\PollVoteTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\PollVote());
                    return new TableGateway('PollVote', $dbAdapter, null, $resultSetPrototype);
                },
            ],
        ];
    }
}
