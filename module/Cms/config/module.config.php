<?php
namespace Cms;

use Base\Factory\BaseFactory;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return array(
    'router' => array(
        'routes' => array(

            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
            'cms' => array(
                'type'    => Literal::class,
                'options' => array(
                    'route'    => '/cms',
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
                            ),
                        ),
                    ),
                    'cms-page' => array(
                        'type' => Segment::class,
                        'options' => array(
                            'route' => '/cms-page/:controller[/:page]',
                            'constraints' => array(
                                'page' => '[0-9]+'
                            ),
                            'defaults' => array(
                                'action' => 'index',
                            )
                        )
                    )
                ),
            ),
        ),
    ),

    'controllers' => array(
        'factories' => array(
            Controller\IndexController::class => BaseFactory::class,
            Controller\MenuController::class => BaseFactory::class,
            Controller\PageController::class => BaseFactory::class,
        ),
        'aliases' => [
            'cms' => Controller\IndexController::class,
            'menu' => Controller\MenuController::class,
            'page' => Controller\PageController::class,

        ]
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);