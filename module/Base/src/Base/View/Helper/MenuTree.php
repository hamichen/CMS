<?php
/**
 * Created by PhpStorm.
 * User: hami
 * Date: 2015/7/18
 * Time: 下午 9:27
 */

namespace Base\View\Helper;


use Zend\View\Helper\AbstractHelper;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MenuTree extends AbstractHelper implements  ServiceLocatorAwareInterface
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

    public function __invoke($id= null, $activeId = null)
    {
        $sm = $this->getServiceLocator();
        /** @var  $em \Doctrine\Orm\EntityManager */
        $em = $sm->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        $menuArr = $em->getRepository('Base\Entity\Menu')->getMenuTree($id,$activeId);
        return json_encode($menuArr);

    }

}