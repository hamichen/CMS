<?php
namespace Admin;

use Base\Controller\BaseControllerFactory;
use Base\Factory\BaseFactory;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'admin' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/admin',
                    'name' => '系統管理',
                    'constraints' => [
                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*'
                    ],
                    'defaults' => [
                        'module' => __NAMESPACE__,
                        'controller' => Controller\IndexController::class,
                        'action' => 'index'
                    ]
                ],
                'child_routes' => [
                    'default' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '[/[:controller[/:action]]]',
                            'constraints' => [
                                'module' => __NAMESPACE__,
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*'
                            ],
                            'defaults' => []
                        ]
                    ],
                    'user' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/user[/:action]',
                            'name' => '使用者管理',
                            'constraints' => [
                                'module' => __NAMESPACE__,
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*'
                            ],
                            'defaults' => [
                                'controller' => Controller\UserController::class,
                                'action' => 'index'
                            ]
                        ]
                    ],
                    'api' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/api[/:action]',
                            'name' => 'API管理',
                            'constraints' => [
                                'module' => __NAMESPACE__,
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*'
                            ],
                            'defaults' => [
                                'controller' => Controller\ApiController::class,
                                'action' => 'index'
                            ]
                        ]
                    ],
                ]
            ]
        ]
    ],

    'controllers' => [
        'factories' => [
            Controller\IndexController::class => BaseFactory::class,
            Controller\UserController::class => BaseFactory::class,
            Controller\ApiController::class => BaseFactory::class,

        ],
        'aliases' => [
            'user' => Controller\UserController::class,
            'api' => Controller\ApiController::class,
        ]

    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view'
        ],
    ],


];
