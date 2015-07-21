<?php
namespace Base\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Session\Container;

class Breadcrums extends AbstractHelper implements ServiceLocatorAwareInterface
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

        $em = $sm->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        $mId = $routeMatch->getParam('id');
        $breadcrumbs = $em->getRepository('Base\Entity\Menu')->systemBreadcrumbsById($mId);
        $str = '<ol class="breadcrumb">';
        $i = 0;
        $len = count($breadcrumbs);
        foreach ($breadcrumbs as $id => $val) {
            if ($id== $mId)
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

        return $str;
        
    }
}
