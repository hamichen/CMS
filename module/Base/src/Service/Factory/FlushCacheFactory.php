<?php

namespace Base\Service\Factory;

use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

class FlushCacheFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $cache = $container->get('cacheApc');

        $cache->removeItem(\Base\Entity\ResourceRepository::BREAKCRUMS_CACHE_KEY);
        $cache->removeItem(\Base\Entity\ResourceRepository::NAV_MENU_CACHE_KEY);
        $cache->removeItem(\Base\Entity\ResourceRepository::RESOURCE_PARENTS_KEY);
        $cache->removeItem(\Base\Controller\Plugin\AclPlugin::ACL_CACHE_KEY);

        return true;
    }
}
