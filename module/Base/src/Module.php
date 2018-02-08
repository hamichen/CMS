<?php

namespace Base;

use Zend\Http\PhpEnvironment\Response;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Mvc\MvcEvent;
use Zend\Console\Adapter\AdapterInterface as ConsoleAdapterInterface;

class Module implements ConfigProviderInterface
{
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }


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
        $translator = $e->getApplication()->getServiceManager()->get('MvcTranslator');
        \Zend\Validator\AbstractValidator::setDefaultTranslator($translator);
        $eventManager->attach('dispatch', array(
            $this,
            'checkUserIsLogin'
        ), 100);

    }


    /**
     * @param $e MvcEvent
     */
    public function checkUserIsLogin($e)
    {

        if (! ($e->getRequest() instanceof \Zend\Console\Request)) {
            $application = $e->getApplication();
            $sm = $application->getServiceManager();
            $sm->get('ControllerPluginManager')
                ->get('AclPlugin')
                ->doAuthorization($e);
        }
    }

    public function getConsoleUsage(ConsoleAdapterInterface $console)
    {

        return array(

            'set-admin <username> <password>'    => '設定系統管理者',
            array('username', '登入帳號'),
            array('password', '登入密碼'),
            'install [re-create-database]'    => '安裝系統',
            array('re-create-database', '重新安裝資料表'),

        );
    }
}