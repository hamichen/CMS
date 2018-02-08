<?php

namespace User;

use Base\Factory\BaseFactory;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'user' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/user',
                    'defaults' => [
                        'module' => __NAMESPACE__,
                        'controller' => Controller\SetController::class,
                        'action' => 'index'
                    ]
                ],
               // 'may_terminate' => true,
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
                    'login' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/login[/:ap]',
                            'defaults' => [
                                'module' => __NAMESPACE__,
                                'controller' => Controller\SignController::class,
                                'action' => 'login'
                            ]
                        ]
                    ],
                    'refresh' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/refresh',
                            'defaults' => [
                                'module' => __NAMESPACE__,
                                'controller' => Controller\SignController::class,
                                'action' => 'refresh'
                            ]
                        ]
                    ],
                    'logout' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/logout',
                            'defaults' => [
                                'module' => __NAMESPACE__,
                                'controller' => Controller\SignController::class,
                                'action' => 'logout'
                            ]
                        ]
                    ],
                    'register' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/register',
                            'defaults' => [
                                'module' => __NAMESPACE__,
                                'controller' => Controller\SignController::class,
                                'action' => 'register'
                            ]
                        ]
                    ],
                    'mail_verify' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/mail-verify/:id',
                            'defaults' => [
                                'module' => __NAMESPACE__,
                                'controller' => Controller\SignController::class,
                                'action' => 'mail-verify'
                            ]
                        ]
                    ],
                    'register-success' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/register-success',
                            'defaults' => [
                                'module' => __NAMESPACE__,
                                'controller' => Controller\SignController::class,
                                'action' => 'register-success'
                            ]
                        ]
                    ],
                    'lostpassword' => [
                        'type' => 'Literal',
                        'options' => [
                            'route' => '/lost-password',
                            'defaults' => [
                                'module' => __NAMESPACE__,
                                'controller' => 'Sign',
                                'action' => 'lost-password'
                            ]
                        ]
                    ],
                    'resetpassword' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/reset-password[/:code]',
                            'defaults' => [
                                'module' => __NAMESPACE__,
                                'controller' => 'Sign',
                                'action' => 'reset-password'
                            ]
                        ]
                    ]
                ]
            ],
            'captcha' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/captcha[/[:id]]',
                    'defaults' => [
                        'module' => __NAMESPACE__,
                        'controller' => Controller\SignController::class,
                        'action' => 'generate'
                    ]
                ]
            ]
        ]
    ],
    'doctrine' => array(
        'driver' => array(

            'sfs_entities' => [
                'class' => '\Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => [
                    __DIR__ . '/../src/Entity'
                ]
            ],
            'orm_center' => [
                'class' => 'Doctrine\ORM\Mapping\Driver\DriverChain',
                'drivers' => [
                    'User\Entity' => 'sfs_entities'
                ]
            ]

        )
    ),
    'controllers' => array(
        'factories' => [
            Controller\SignController::class => BaseFactory::class,
            Controller\SetController::class => BaseFactory::class,
        ],
        'aliases' => [
            'user-sign' => Controller\SignController::class,
            'user-set' => Controller\SetController::class
        ]
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view'
        )
    ),



];

