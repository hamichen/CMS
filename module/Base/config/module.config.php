<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Base;

return array(
    'service_manager' => array(
        'factories' => array(
            'Zend\Log' => 'Base\Qualified\NS\LogFactory',
            'cacheApc' => 'Base\Qualified\NS\CacheApcFactory',
            'SchoolSession' => 'Base\Qualified\NS\SchoolSessionFactory',
            'acl' => 'Base\Qualified\NS\AclFactory'
        ),
    ),
    'controller_plugins' => array(
        'invokables' => array(
            'Aclplugin' => 'Base\Controller\Plugin\Aclplugin',
            'Params' => 'Base\Controller\Plugin\Params',
            'UserIdentity' => 'Base\Controller\Plugin\UserIdentity',
            'FileStorePath' => 'Base\Controller\Plugin\FileStorePath',
            'Download' => 'Base\Controller\Plugin\Download',
        )
    ),

    'controllers' => array(
        'invokables' => array(
            'Base\Controller\SetAdmin' => 'Base\Controller\SetAdminController',
        ),
    ),

    'view_manager' => array(
        'strategies' => array(
            'ViewJsonStrategy'
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view'
        ),
    ),

    'view_helpers' => array(
        'invokables' => array(
            'ChineseNumber' => 'Base\View\Helper\ChineseNumber',
            'ModuleParam' => 'Base\View\Helper\ModuleParam',
            'RocDate' => 'Base\View\Helper\RocDate',
            'WordWrap' => 'Base\View\Helper\WordWrap',
            'ControllerName' => 'Base\View\Helper\ControllerName',
            'LeftMenu' => 'Base\View\Helper\LeftMenu',
            'MenuTree' => 'Base\View\Helper\MenuTree',
            'FileType' => 'Base\View\Helper\FileType',
            'Breadcrums' => 'Base\View\Helper\Breadcrums',
        )
    ),
    'zfctwig' => array(
        'helper_manager' => array(
            'invokables' => array(
                'partial'  => 'Zend\View\Helper\Partial',
                'paginationControl'  => 'Zend\View\Helper\PaginationControl',
            ),
        ),
        'extensions' => array(
            //  'scoreRender' => 'Base\Twig\Extension\ScoreRender',
        ),
    ),
    'doctrine' => array(
        'driver' => array(
            __NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/' . __NAMESPACE__ . '/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                )
            ),

        )
    ),
    'console' => array(
        'router' => array(
            'routes' => array(

                'set-admin' => array(
                    'options' => array(
                        'route'    => 'set-admin <username> <password>',
                        'defaults' => array(
                            'controller' => 'Base\Controller\SetAdmin',
                            'action'     => 'run',
                        ),
                    ),
                ),
            ),
        ),
    ),
);
