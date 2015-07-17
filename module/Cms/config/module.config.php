<?php
namespace Cms;

return array(
    'router' => array(
        'routes' => array(

            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
            'cms' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/cms',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Cms\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
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
                        'type' => 'Segment',
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
        'invokables' => array(
            'Cms\Controller\Index' => 'Cms\Controller\IndexController',
            'Cms\Controller\Menu' => 'Cms\Controller\MenuController',
            'Cms\Controller\Page' => 'Cms\Controller\PageController'
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);