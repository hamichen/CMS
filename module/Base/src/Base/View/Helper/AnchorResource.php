<?php

namespace Base\View\Helper;

/**
 * 個別化選單
 */
use Zend\View\Helper\AbstractHelper;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AnchorResource extends AbstractHelper implements ServiceLocatorAwareInterface {


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
        $server_url = $this->getView()->serverUrl(true);
        $session = $sm->getServiceLocator()->get('SchoolSession');
        if (!$uriArr= $session->user_anchor) {
            $auth = $sm->getServiceLocator()->get ( 'doctrine.authenticationservice.orm_default' );

            /** @var \Doctrine\Orm\EntityManager $em */
            $em = $sm->getServiceLocator()->get('doctrine.entitymanager.orm_default');
            $res = $em->createQuery('SELECT u FROM Base\Entity\ResourceUser u
                LEFT JOIN u.user user
                LEFT JOIN u.resource resource
                WHERE user.id=:user_id
                ORDER BY resource.id')
                ->setParameter('user_id', $auth->getIdentity()->getId())
                ->getArrayResult();
            $uriArr = ['base'];
            foreach ($res as $row) {
                $uriArr[] = $row['uri'];
            }
            $session->user_anchor = $uriArr;
        }
        if (in_array($server_url, $uriArr))
            return  '<span class="btn btn-circle btn-remove-anchor"><i class="fa fa-anchor text-red" title="移除自我的選單"></i></span>';
        else
        return '<span class="btn btn-circle btn-anchor"><i class="fa fa-anchor text-blue" title="加到我的選單"></i></span>';
    }


} 