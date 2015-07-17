<?php
namespace Base\Qualified\NS;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Session\Container;

class CacheApcFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        // TODO 不同學校使用不同 cache 檔案
        $session = new Container('school');

      /*  if (!$school = $session->school)
            return false;*/
        $school = $session->school;

        /*$cache = \Zend\Cache\StorageFactory::factory(array(
            'adapter' => 'apc',
            'plugins' => array(
                'exception_handler' => array(
                    'throw_exceptions' => false
                ),
             
            ),
             'options' => array(
                 'namespace' => 'school_'.$school['edu_no'],
            
             ),
        ));*/
        $cache = \Zend\Cache\StorageFactory::factory(array(
            'adapter' => 'memcached',
            'lifetime' => 7200,
            'plugins' => array(
                'exception_handler' => array(
                    'throw_exceptions' => false
                ),

            ),
            'options' => array(
                'namespace' => 'school_'.$school['edu_no'],
                'servers'   => array(
                    array(
                        '127.0.0.1',11211
                    ),
                ),
                'liboptions' => array (
                    'COMPRESSION' => true,
                    'binary_protocol' => true,
                    'no_block' => true,
                    'connect_timeout' => 100
                )

            ),
        ));

        return $cache;
    }
}
