<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Petition;

use Zend\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'petition' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/[:lang/]petition/:pid',
                    'constraints' => [
                        'lang' => '[a-z]{2}_[A-Z]{2}',
                        'pid'  => '([0-9]+|latest)',
                    ],
                    'defaults' => [
                        'controller' => Controller\PetitionController::class,
                        'action'     => 'index',
                        'lang'       => 'fr_FR',
                    ],
                ],
            ],
            'petitions' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/[:lang/]petitions',
                    'constraints' => [
                        'lang' => '[a-z]{2}_[A-Z]{2}',
                    ],
                    'defaults' => [
                        'controller' => Controller\PetitionsController::class,
                        'action'     => 'index',
                        'lang'       => 'fr_FR',
                    ],
                ],
            ],
        ],
    ],
    'view_manager' => [
        'doctype' => 'HTML5',
        'template_map' => [
            'petition/petition/index'        => __DIR__ . '/../view/petition/petition/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
