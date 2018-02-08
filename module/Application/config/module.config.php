<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Base\Factory\BaseFactory;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'home' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'module' => __NAMESPACE__,
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],

            'file' => [
                'type' => Segment::class,
                'options' => [
                    'route'    => '/file[/:action/:id]',
                    'defaults' => [
                        'module' => __NAMESPACE__,
                        'controller'    => Controller\FileController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'view' => array(
                'type'    => Segment::class,
                'options' => array(
                    'route'    => '/view/:id[/:name].html',
//                    'constraints' => array(
//                        'id' => '[0-9]+'
//                    ),
                    'defaults' => array(
                        'module' => __NAMESPACE__,
                        'controller'    => Controller\IndexController::class,
                        'action'        => 'view',
                    ),
                ),
                'may_terminate' => false,

            ),


            'application' => array(
                'type'    => Literal::class,
                'options' => array(
                    'route'    => '/application',
                    'defaults' => array(
                        'module' => __NAMESPACE__,
                        'controller'    => Controller\IndexController::class,
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => Segment::class,
                        'options' => array(
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                                'module' => __NAMESPACE__,
                                'controller'    => Controller\IndexController::class,
                            ),
                        ),
                    ),

                ),
            ),

            'index-file-page' => array(
                'type' => Segment::class,
                'options' => array(
                    'route' => '/browsing[/:page][/:select][/:val]',
                    'constraints' => array(
                        'page' => '[0-9]+'
                    ),
                    'defaults' => array(
                        'module' => __NAMESPACE__,
                        'controller'    => Controller\FileController::class,
                        'action' => 'index',
                    )
                )
            ),
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => BaseFactory::class,
            Controller\FileController::class => BaseFactory::class,
        ],
        'aliases' => [
            'index' => Controller\IndexController::class,
            'file' => Controller\FileController::class,
        ]

    ],
     'view_helpers' => [
        'factories' => [
            View\Helper\SelectedLayout::class => View\Helper\Service\SelectLayoutFactory::class,
            View\Helper\FilterWhitespace::class => View\Helper\Service\FilterWhitespaceFactory::class,
        ],
        'aliases' => [
            'SelectedLayout' => View\Helper\SelectedLayout::class,
            'FilterWhitespace' => View\Helper\FilterWhitespace::class,
        ],

    ],

    'view_manager' => [
        'strategies' => [
            'ViewJsonStrategy'
        ],
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => [
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
