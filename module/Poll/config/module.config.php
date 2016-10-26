<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Poll;

use Zend\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'poll' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/[:lang/]poll/:pid',
                    'constraints' => [
                        'lang' => '[a-z]{2}_[A-Z]{2}',
                        'pid'  => '([0-9]+|latest)',
                    ],
                    'defaults' => [
                        'controller' => Controller\PollController::class,
                        'action'     => 'index',
                        'lang'       => 'fr_FR',
                    ],
                ],
            ],
            'answer' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/[:lang/]poll/:pid/answer/:aid',
                    'constraints' => [
                        'lang'   => '[a-z]{2}_[A-Z]{2}',
                        'pid'    => '[0-9]+',
                        'aid'    => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\PollAnswerController::class,
                        'action'     => 'index',
                        'lang'       => 'fr_FR',
                    ],
                ],
            ],
            'vote' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/[:lang/]poll/:pid/answer/:aid/:action[/:param]',
                    'constraints' => [
                        'lang'   => '[a-z]{2}_[A-Z]{2}',
                        'pid'    => '[0-9]+',
                        'aid'    => '[0-9]+',
                        'action' => '[a-zA-Z]+',
                        'param'  => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\PollVoteController::class,
                        'lang'       => 'fr_FR',
                    ],
                ],
            ],
        ],
    ],
    'view_manager' => [
        'doctype' => 'HTML5',
        'template_map' => [
            'poll/poll/index'        => __DIR__ . '/../view/poll/poll/index.phtml',
            'poll/poll-answer/index' => __DIR__ . '/../view/poll/answer/index.phtml',
            'poll/poll-vote/vote'    => __DIR__ . '/../view/poll/vote/vote.phtml',

        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
