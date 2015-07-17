<?php
namespace Base\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ControllerName extends AbstractHelper implements ServiceLocatorAwareInterface {


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


    public function __invoke() {

        $sm = $this->getServiceLocator();
        $router = $sm->getServiceLocator()->get('router');
        $request = $sm->getServiceLocator()->get('request');
        $routeMatch = $router->match($request);
        $controllerName = $routeMatch->getParam('controller');
        return $controllerName;
    }



}
