<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Mvc\MvcEvent;

class Module implements ConfigProviderInterface
{
    const VERSION = '3.0.0dev';

    /**
     * Configure the translator locale based on current URL. Default is en_US
     */
    public function onBootstrap(MvcEvent $e)
    {
        $translator = $e->getApplication()->getServiceManager()->get('Zend\I18n\Translator\TranslatorInterface');

        $setTranslatorLocale = function($lang) use ($translator) {
            $translator
              ->setLocale($lang)
              ->setFallbackLocale('fr_FR');
        };

        $getLocale = function ($event)
        {
            return $event->getRouteMatch()->getParam('lang');
        };

        $findLocaleOnError = function()
        {
            $requested_uri = $_SERVER['REQUEST_URI'];
            if (! preg_match('/^\\/([a-z]{2}_[A-Z]{2})\\/.*$/', $requested_uri, $matches))
                return null;

            if (count($matches) != 2)
                return null;

            return $matches[1];
        };

        $e->getApplication()->getEventManager()->attach(
            MvcEvent::EVENT_ROUTE,
            function ($event) use ($setTranslatorLocale, $getLocale) {
                $setTranslatorLocale($getLocale($event));
            }
        );

        $e->getApplication()->getEventManager()->attach(
            MvcEvent::EVENT_DISPATCH_ERROR,
            function ($event) use ($setTranslatorLocale, $findLocaleOnError) {
                $setTranslatorLocale($findLocaleOnError());
            }
        );

        $e->getApplication()->getEventManager()->attach(
            MvcEvent::EVENT_RENDER_ERROR,
            function ($event) use ($setTranslatorLocale, $findLocaleOnError) {
                $setTranslatorLocale($findLocaleOnError());
            }
        );
    }

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
                        $container->get(\Poll\Model\PollTable::class),
                        $container->get(\Petition\Model\PetitionTable::class)
                    );
                },
            ],
        ];
    }

}
