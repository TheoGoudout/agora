<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
            'home' => [
                'type' => Segment::class,
                'options' => [
                    'route'    => '/[:lang/][:action]',
                    'constraints' => [
                        'lang'   => '[a-z]{2}_[A-Z]{2}',
                        'action' => '[a-z]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\HomeController::class,
                        'action'     => 'index',
                        'lang'       => 'fr_FR',
                    ],
                ],
            ],
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => [
            'layout/layout'            => __DIR__ . '/../view/layout/layout.phtml',
            'application/home/index'   => __DIR__ . '/../view/application/home/index.phtml',
            'application/home/contact' => __DIR__ . '/../view/application/home/contact.phtml',
            'application/home/about'   => __DIR__ . '/../view/application/home/about.phtml',
            'error/404'                => __DIR__ . '/../view/error/404.phtml',
            'error/index'              => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
