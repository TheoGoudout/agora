<?php

namespace Question;

use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
            'home' => [
                'type' => Segment::class,
                'options' => [
                    'route'    => '/[:lang]',
                    'constraints' => [
                        'lang'   => '[a-z]{2}_[A-Z]{2}',
                    ],
                    'defaults' => [
                        'controller' => Controller\QuestionController::class,
                        'action'     => 'index',
                        'lang'       => 'en_US',
                    ],
                ],
            ],
            'question' => [
                'type'    => 'segment',
                'options' => [
                    'route'    => '/:lang/question[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                        'lang'   => '[a-z]{2}_[A-Z]{2}',
                    ],
                    'defaults' => [
                        'controller' => Controller\QuestionController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            'question' => __DIR__ . '/../view',
        ],
    ],
];