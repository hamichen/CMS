<?php
namespace Base\Qualified\NS;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Session\Container;

class LogFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {

        $logPath = './data/log';
        if (!is_dir($logPath)){
            mkdir($logPath, 0777, true);
        }
        $log = new \Zend\Log\Logger();
        $writer = new \Zend\Log\Writer\Stream($logPath.'/'.'system.log');
        $log->addWriter($writer);
        return $log;
    }
}