<?php

namespace Base\View\Helper;

/**
 * 個別化選單
 */
use Zend\View\Helper\AbstractHelper;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AnchorMenu extends AbstractHelper implements ServiceLocatorAwareInterface {


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

        $session = $sm->getServiceLocator()->get('SchoolSession');
        
        $resultStr = '';
        if (count($session->user_anchor)>1) {
            $configs = $sm->getServiceLocator()->get('config')['navigation']['default'];
          //  print_r($configs);
            $resultStr = '<li><div class="dropdown">
            <button class="btn btn-block btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown">
                我的選單
            <span class="caret"></span>
            </button>
              <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">';
            foreach ($session->user_anchor as $val) {
                if ($val == 'base') continue;
                $tempArr = explode('//',$val);
                $tempArr2 = explode('/', $tempArr[1]);
                if (!isset($configs[$tempArr2[1]]['label']))
                    continue;
                $str = $configs[$tempArr2[1]]['label'];
                if (isset($configs[$tempArr2[1]]['pages'][$tempArr2[2]]['label']))
                    $str .= ' - <span class="text-success">'.$configs[$tempArr2[1]]['pages'][$tempArr2[2]]['label'].'</span>';

                $resultStr .= '<li role="presentation"><a role="menuitem" tabindex="-1" href="'.$val.'">'.$str.'</a></li>';
            }
            $resultStr .= '</ul></div></li>';
        }

        return $resultStr;

    }


} 