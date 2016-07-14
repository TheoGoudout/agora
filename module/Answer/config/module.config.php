<?php

namespace Answer;

use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
            'answer' => [
                'type' => Segment::class,
                'options' => [
                    'route'    => '/:lang/answers/:qid[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                        'qid'    => '[0-9]+',
                        'lang'   => '[a-z]{2}_[A-Z]{2}',
                    ],
                    'defaults' => [
                        'controller' => Controller\AnswerController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            'answer' => __DIR__ . '/../view',
        ],
    ],
];