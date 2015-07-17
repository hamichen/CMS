<?php
namespace Base\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Session\Container;

class SystemBreadcrums extends AbstractHelper implements ServiceLocatorAwareInterface
{

    /**
     * Set the service locator.
     *
     * @param ServiceLocatorInterface $serviceLocator            
     * @return CustomHelper
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        return $this;
    }

    /**
     * Get the service locator.
     *
     * @return \Zend\ServiceManager\ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    public function __invoke()
    {
        $sm = $this->getServiceLocator();
        $router = $sm->getServiceLocator()->get('router');
        $request = $sm->getServiceLocator()->get('request');
        $routeMatch = $router->match($request);
        if (! $routeMatch)
            return false;
            // if ($routeMatch->getParam('id'))
       // $controllerName = $routeMatch->getParam('controller');
        $module = $routeMatch->getParam('__NAMESPACE__');
        $module = preg_replace('/(?<!^)([A-Z])/', '-\\1', $module);
        $moduleName = strtolower(substr($module, 0, strpos($module, '\\')));
        
        $em = $sm->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        if ($moduleName) {
            if ($res = $em->getRepository('Base\Entity\Resource')->findOneBy(array(
                'resource_name' => $moduleName
            )))
            $id = $res->getId();
            else
                return false;
        } else
            $id = $routeMatch->getParam('id');
        
        $cache = $sm->getServiceLocator()->get('cacheApc');
        
        if (!$result = $cache->getItem(\Base\Entity\ResourceRepository::BREAKCRUMS_CACHE_KEY)) {
            $res = $em->getRepository('Base\Entity\Resource')->findAll();
            $result = array();
            foreach($res as $row) {
                $breadcrumbs = $em->getRepository('Base\Entity\Resource')->systemBreadcrumbsById($row->getId());
                $str = '<ol class="breadcrumb">';
                $i = 0;
                $len = count($breadcrumbs);
                foreach ($breadcrumbs as $id => $val) {
                    if ($val['name'] == $moduleName)
                        $str .= '<li class="active">';
                    else
                        $str .= '<li>';
                    if ($i ++ < $len - 1)
                        $str .= '<a href="/m/' . $id . '">' . $val['name'] . '</a>';
                    else
                        $str .= $val['name'];
                    $str .= '</li>';
                }
                $str .= '</ol>';
                $result[$row->getId()] = $str;
                
            }
            $cache->setItem(\Base\Entity\ResourceRepository::BREAKCRUMS_CACHE_KEY, $result);
        }
        if (isset($result[$id]))
        return $result[$id];
        else 
            return false;
        
    }
}
