<?php
namespace Base\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class LeftMenu extends AbstractHelper implements ServiceLocatorAwareInterface
{
    protected $moduleInfo = [];
    protected $navigation = [];
    protected $basePath = '';
    protected $urlHelper;
    protected $moduleName;
    protected $resourceParentsArr = [];
    /**
     * @var $acl \Base\Acl\Acl
     */
    protected $acl;

    protected $role;

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

    public function __invoke($id=null)
    {
        $sm = $this->getServiceLocator();
        /** @var  $em \Doctrine\Orm\EntityManager */
        $em = $sm->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        return $em->getRepository('Base\Entity\Menu')->getMenuArray($id);


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


}
