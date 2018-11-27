<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Base;

use Base\Factory\BaseFactory;
use Base\Service\TranslatorDelegator;
use Zend\Log\Logger;
use Zend\Authentication\AuthenticationService;
return [

    'doctrine' => [
        'driver' =>  [
            __NAMESPACE__ . '_driver' => [
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => [__DIR__ . '/../src/Entity']
            ],
            'orm_default' => [
                'drivers' => [
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                ]
            ],
            __NAMESPACE__ . '_odm_driver' => [
                'class' => 'Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver',
                'paths' => [__DIR__ . '/../src/Document']
            ],
            'odm_default' => [
                'drivers' => [
                    __NAMESPACE__ . '\Document' => __NAMESPACE__ . '_odm_driver'
                ]
            ]
        ],

    ],

    'controllers' => [
        'factories' => [
            Controller\InstallController::class => Factory\BaseFactory::class,
            Controller\SetAdminController::class => Factory\BaseFactory::class,
        ],
        'aliases' => [
            'install' => Controller\InstallController::class,
            'set-admin' => Controller\SetAdminController::class,
        ]
    ],

    'controller_plugins' => array(
        'invokables' => array(
            'AclPlugin' => 'Base\Controller\Plugin\AclPlugin',
        )
    ),

    'service_manager' => [
        'abstract_factories' => [
            'Zend\Log\LoggerAbstractServiceFactory',
        ],
        'aliases' => [
            'cacheApc' => Service\Factory\CacheApcFactory::class,
            'acl' => Service\Acl::class,
            'FlushCache' => Service\Factory\FlushCacheFactory::class,
            'Log' => Service\Factory\LogFactory::class,
            'translator' => 'MvcTranslator',

        ],
        'factories' => [
            Service\Factory\CacheApcFactory::class => Service\Factory\CacheApcFactory::class,
            Service\Factory\FlushCacheFactory::class => Service\Factory\FlushCacheFactory::class,
            Service\Acl::class => Service\Factory\AclFactory::class,
            Service\Factory\LogFactory::class => Service\Factory\LogFactory::class,
            \Zend\I18n\Translator\TranslatorInterface::class => \Zend\I18n\Translator\TranslatorServiceFactory::class,
            AuthenticationService::class => Factory\AuthenticationServiceFactory::class,
        ],

    ],

    'view_helpers' => [
        'invokables' => [
            'formRow' => Form\View\Helper\RequiredMarkInFormLabel::class,
        ],
        'factories' => [
            View\Helper\UserIdentity::class => View\Helper\Service\UserIdentityFactory::class,
            View\Helper\PageTitle::class => BaseFactory::class,
            View\Helper\Params::class => View\Helper\Service\ParamsFactory::class,
            View\Helper\Config::class => BaseFactory::class,
            View\Helper\ModuleTitle::class => BaseFactory::class,
            View\Helper\LeftMenu::class => BaseFactory::class,
            View\Helper\ActivePage::class => BaseFactory::class,
            View\Helper\BreadCrumb::class => BaseFactory::class,
            View\Helper\SystemPage::class => View\Helper\Service\SystemPageFactory::class,
            View\Helper\SearchWord::class => View\Helper\Service\SearchWordFactory::class,

        ],
        'aliases' => [
            'userIdentity' =>  View\Helper\UserIdentity::class,
            'layoutBasePath' =>  View\Helper\LayoutBasePath::class,
            'PageTitle' =>  View\Helper\PageTitle::class,
            'params' => View\Helper\Params::class,
            'config' => View\Helper\Config::class,
            'ModuleTitle' => View\Helper\ModuleTitle::class,
            'ActivePage' => View\Helper\ActivePage::class,
            'LeftMenu' => View\Helper\LeftMenu::class,
            'breadCrumb' => View\Helper\BreadCrumb::class,
            'SystemPage' => View\Helper\SystemPage::class,
            'searchWord' => View\Helper\SearchWord::class,
        ],
    ],
    'zfctwig' => array(
        'environment_options' => array(
            'debug' => true,
    //        'cache' => __DIR__.'/../../../data/cache'
        ),
        'extensions' => array(
            'Twig_Extension_Debug'

        ),
        'helper_manager' => array(
            'invokables' => array(
                'partial'  => 'Zend\View\Helper\Partial',
                'paginationControl'  => 'Zend\View\Helper\PaginationControl',
            ),
        ),
    ),

    'log' => [
        'MyLogger' => [
            'writers' => [
                [
                    'name' => 'stream',
                    'priority' => Logger::DEBUG,
                    'options' => [
                        'stream' => 'data/app.log',
                       /* 'formatter' => [
                            'name' => 'MyFormatter',
                        ],
                        'filters' => [
                            [
                                'name' => 'MyFilter',
                            ],
                        ],*/
                    ],
                ],
            ],
        ],
    ],

    'translator' => [
        'locale' => 'zh_TW',
        'translation_files' => array(
            array(
                'type'      => 'phparray',
                'filename'  => __DIR__ . '/../language/zh_TW/Zend_Validate.php',
            ),
        ),
    ],

    'console' => array(
        'router' => array(
            'routes' => array(

                'set-admin' => array(
                    'options' => array(
                        'route'    => 'set-admin <username> <password>',
                        'defaults' => array(
                            'controller' => Controller\SetAdminController::class,
                            'action'     => 'run',
                        ),
                    ),
                ),
                'install' => array(
                    'options' => array(
                        'route'    => 'install [re-create-database]',
                        'defaults' => array(
                            'controller' => Controller\InstallController::class,
                            'action'     => 'run',
                        ),
                    ),
                ),
                'upgrade' => array(
                    'options' => array(
                        'route'    => 'upgrade',
                        'defaults' => array(
                            'controller' => Controller\UpgradeController::class,
                            'action'     => 'run',
                        ),
                    ),
                ),

            ),
        ),
    ),
];
