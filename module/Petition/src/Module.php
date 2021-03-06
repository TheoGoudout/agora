<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Petition;

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
                Controller\PetitionController::class => function($container) {
                    return new Controller\PetitionController(
                        $container->get(\Zend\I18n\Translator\TranslatorInterface::class),
                        $container->get(Model\PetitionTable::class)
                    );
                },
                Controller\PetitionsController::class => function($container) {
                    return new Controller\PetitionsController(
                        $container->get(\Zend\I18n\Translator\TranslatorInterface::class),
                        $container->get(Model\PetitionTable::class)
                    );
                },
                Controller\PetitionMailingListController::class => function($container) {
                    return new Controller\PetitionMailingListController(
                        $container->get(\Zend\I18n\Translator\TranslatorInterface::class),
                        $container->get(Model\PetitionMailingListTable::class)
                    );
                },
                Controller\PetitionSignatureController::class => function($container) {
                    return new Controller\PetitionSignatureController(
                        $container->get(\Zend\I18n\Translator\TranslatorInterface::class),
                        $container->get(Model\PetitionSignatureTable::class)
                    );
                },
                Controller\PetitionStatusController::class => function($container) {
                    return new Controller\PetitionStatusController(
                        $container->get(\Zend\I18n\Translator\TranslatorInterface::class),
                        $container->get(Model\PetitionStatusTable::class)
                    );
                },
            ],
        ];
    }

    public function getServiceConfig()
    {
        return [
            'factories' => [
                Model\PetitionTable::class => function($container) {
                    return new Model\PetitionTable(
                        $container->get(\Zend\I18n\Translator\TranslatorInterface::class),
                        $container->get(Model\PetitionTableGateway::class),
                        $container->get(Model\PetitionMailingListTable::class),
                        $container->get(Model\PetitionSignatureTable::class),
                        $container->get(Model\PetitionStatusTable::class)
                    );
                },
                Model\PetitionMailingListTable::class => function($container) {
                    return new Model\PetitionMailingListTable(
                        $container->get(\Zend\I18n\Translator\TranslatorInterface::class),
                        $container->get(Model\PetitionMailingListTableGateway::class)
                    );
                },
                Model\PetitionSignatureTable::class => function($container) {
                    return new Model\PetitionSignatureTable(
                        $container->get(\Zend\I18n\Translator\TranslatorInterface::class),
                        $container->get(Model\PetitionSignatureTableGateway::class)
                    );
                },
                Model\PetitionStatusTable::class => function($container) {
                    return new Model\PetitionStatusTable(
                        $container->get(\Zend\I18n\Translator\TranslatorInterface::class),
                        $container->get(Model\PetitionStatusTableGateway::class)
                    );
                },

                Model\PetitionTableGateway::class => function ($container) {
                    $translator = $container->get(\Zend\I18n\Translator\TranslatorInterface::class);
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Petition($translator));
                    return new TableGateway('Petition', $dbAdapter, null, $resultSetPrototype);
                },
                Model\PetitionMailingListTableGateway::class => function ($container) {
                    $translator = $container->get(\Zend\I18n\Translator\TranslatorInterface::class);
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\PetitionMailingList($translator));
                    return new TableGateway('PetitionMailingList', $dbAdapter, null, $resultSetPrototype);
                },
                Model\PetitionSignatureTableGateway::class => function ($container) {
                    $translator = $container->get(\Zend\I18n\Translator\TranslatorInterface::class);
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\PetitionSignature($translator));
                    return new TableGateway('PetitionSignature', $dbAdapter, null, $resultSetPrototype);
                },
                Model\PetitionStatusTableGateway::class => function ($container) {
                    $translator = $container->get(\Zend\I18n\Translator\TranslatorInterface::class);
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\PetitionStatus($translator));
                    return new TableGateway('PetitionStatus', $dbAdapter, null, $resultSetPrototype);
                },
            ],
        ];
    }
}
