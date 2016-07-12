<?php

namespace Question;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Mvc\MvcEvent;

class Module implements ConfigProviderInterface
{
    public function onBootstrap(MvcEvent $e)
    {
        $translator = $e->getApplication()->getServiceManager()->get('Zend\I18n\Translator\TranslatorInterface');

        $e->getApplication()->getEventManager()->attach(MvcEvent::EVENT_ROUTE,
            function($event) use ($translator) {
                $lang = $event->getRouteMatch()->getParam('lang');

                $translator
                  ->setLocale($lang ? $lang : 'en_US')
                  ->setFallbackLocale('en_US');
            }
        );
    }

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
                        $container->get(Model\QuestionTable::class),
                        $container->get('Zend\I18n\Translator\TranslatorInterface')
                    );
                },
            ],
        ];
    }
}