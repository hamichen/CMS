<?php
namespace Base\Service\Factory;

use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;
use Zend\Session\Container;

class CacheMemcachedFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
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
                        'memcached',11211
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
