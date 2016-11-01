<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

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
                Controller\HomeController::class => function($container) {
                    return new Controller\HomeController(
                        $container->get(\Zend\I18n\Translator\TranslatorInterface::class),
                        $container->get(\Poll\Model\PollTable::class),
                        $container->get(\Petition\Model\PetitionTable::class)
                    );
                },
            ],
        ];
    }

}
