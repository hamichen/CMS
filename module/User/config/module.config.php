<?php
namespace User;

return array(
    'router' => array(
        'routes' => array(
            'user' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/user',
                    'defaults' => array(
                        '__NAMESPACE__' => 'User\Controller',
                        'controller' => 'Set',
                        'action' => 'index'
                    )
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*'
                            ),
                            'defaults' => array()
                        )
                    ),
                    'login' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/login[/:ap]',
                            'defaults' => array(
                                '__NAMESPACE__' => 'User\Controller',
                                'controller' => 'Sign',
                                'action' => 'login'
                            )
                        )
                    ),
                    'logout' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/logout',
                            'defaults' => array(
                                '__NAMESPACE__' => 'User\Controller',
                                'controller' => 'Sign',
                                'action' => 'logout'
                            )
                        )
                    ),
                    'lostpassword' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/lost-password',
                            'defaults' => array(
                                '__NAMESPACE__' => 'User\Controller',
                                'controller' => 'Sign',
                                'action' => 'lost-password'
                            )
                        )
                    ),
                    'resetpassword' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/reset-password[/:code]',
                            'defaults' => array(
                                '__NAMESPACE__' => 'User\Controller',
                                'controller' => 'Sign',
                                'action' => 'reset-password'
                            )
                        )
                    )
                )
            ),
            'captcha' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/captcha[/[:id]]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'User\Controller',
                        'controller' => 'sign',
                        'action' => 'generate'
                    )
                )
            )
        )
    ),
    'controllers' => array(
        'invokables' => array(
            'User\Controller\Sign' => 'User\Controller\SignController',
            'User\Controller\Set' => 'User\Controller\SetController'
        )
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view'
        )
    ),
    

);

