<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Base;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Console\Adapter\AdapterInterface as ConsoleAdapterInterface;


class Module
{
    public function onBootstrap(MvcEvent $e)
    {

        $app = $e->getApplication();
        $sm = $app->getServiceManager();
        $config = $sm->get('Configuration');
        if (isset($config['phpSettings'])) {
            $phpSettings = $config['phpSettings'];
            foreach ($phpSettings as $key => $value) {
                ini_set($key, $value);
            }
        }

        $eventManager = $e->getApplication()->getEventManager();
        $eventManager->attach('dispatch', array(
            $this,
            'checkUserIsLogin'
        ), 100);

    }


    public function checkUserIsLogin($e)
    {
        if (!($e->getRequest() instanceof \Zend\Console\Request)) {

            $application = $e->getApplication();
            $sm = $application->getServiceManager();
            $sm->get('ControllerPluginManager')
                ->get('Aclplugin')
                ->doAuthorization($e);
        }
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'AuthService' => function ($serviceManager) {
                    return $serviceManager->get('doctrine.authenticationservice.orm_default');
                },

            )
        );
    }

    public function getViewHelperConfig()
    {
        return array(
            'factories' => array(
                'userIdentity' => function ($sm) {
                    $locator = $sm->getServiceLocator();
                    $viewHelper = new View\Helper\UserIdentity();
                    $viewHelper->setAuthService($locator->get('doctrine.authenticationservice.orm_default'));
                    return $viewHelper;
                },
                'Params' => function (ServiceLocatorInterface $helpers) {
                    $services = $helpers->getServiceLocator();
                    $app = $services->get('Application');
                    return new View\Helper\Params($app->getRequest(), $app->getMvcEvent());
                },
                'config' => function ($serviceManager) {
                    $helper = new View\Helper\Config($serviceManager);
                    return $helper;
                },
            )
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getConsoleUsage(ConsoleAdapterInterface $console)
    {

        return array(


            'set-admin <username> <password>'    => '設定系統管理者',
            array('username', '登入帳號'),
            array('password', '登入密碼'),

            'version | --version' => 'display current Zend Framework version',


        );
    }

}
