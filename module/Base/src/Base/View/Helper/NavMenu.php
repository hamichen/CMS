<?php
namespace Base\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Session\Container;

class NavMenu extends AbstractHelper implements ServiceLocatorAwareInterface {


    protected $_acl;

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

    public function __invoke($options = array()) {

        $sm = $this->getServiceLocator();
        $router = $sm->getServiceLocator()->get('router');
        $request = $sm->getServiceLocator()->get('request');
        $routeMatch = $router->match($request);
        $controllerName = $routeMatch->getParam('controller');
        $module = $routeMatch->getParam('__NAMESPACE__');
        $module = preg_replace('/(?<!^)([A-Z])/', '-\\1', $module);
        $moduleName = strtolower(substr($module,0,strpos($module,'\\')));
        $configs = $sm->getServiceLocator()->get('config');
        $menuNav = $configs['navigation']['default'];


        $menus = $menuNav[$moduleName]['pages'];
        $route = $menuNav[$moduleName]['route'];

        $session = $sm->getServiceLocator()->get ('SchoolSession');
        // 判斷是否為導師
        $tutorCount  = count($session->tutors);
        $this->_acl = $session->acl;
        $auth = $sm->getServiceLocator()->get('doctrine.authenticationservice.orm_default');
        $role = (! $auth->getIdentity() instanceof \Base\Entity\User ) ? 'system-訪客' : $auth->getIdentity()->getUserName();
        $str ='<ul class="nav nav-tabs">';
        $namespace = $moduleName;
        $i = 1;
    //    print_r($menus);
        foreach ($menus as $val) {
            //判斷是否為導師模組
            if(isset($val['tutor']) and $tutorCount ==0)
                continue;
            // 判斷是否為學校模組，有設定 attr 的屬性才判斷
            if (isset($val['attr'])) {
                if (!in_array($session->school['attr_name'],$val['attr']))
                    continue;
            }
            $resource = $namespace.':'.$val['controller'];
            $isAllow = false;
            if ($this->_acl->hasResource($namespace))
                $isAllow = $this->_acl->isAllowed($role, $namespace );

            if (!$isAllow) {
                if ($this->_acl->hasResource($resource))
                    $isAllow = $this->_acl->isAllowed($role ,$resource);
            }


            if (isset($val['pages'])) {
                foreach ($val['pages'] as $val2){
                    if (!isset($val2['controller'])) continue;
                    $resource = $namespace.':'.$val2['controller'];
                    if ($this->_acl->hasResource($resource))
                        $isAllow = $this->_acl->isAllowed($role ,$resource);

                }
            }

            if ($isAllow) {

                $badge = '';
                if (isset($val['numbered']) and $val['numbered'])
                    $badge = '<span class="badge">' . $i . '</span>';


                $class = isset($val['class']) ? $val['class'] : '';

                $fa_class = isset($val['fa-class']) ? "<i class='{$val['fa-class']}'></i> " : '';

                $j = 1;
                if (isset($val['pages'])) {


                    if (in_array($controllerName, array_keys($val['pages'])))
                        $str .= '<li class="active ' . $class . '">';
                    else
                        $str .= '<li class="' . $class . '">';

                    $str .= '<a class="dropdown-toggle" data-toggle="dropdown" href="/' . $route . '/' . $val['controller'] . '">' . $fa_class . $val['label'] . ' <b class="caret"></b> ' . $badge . '</a>';
                    $str .= '<ul class="dropdown-menu">';
                    foreach ($val['pages'] as $val2) {
                        //判斷是否為導師模組
                        if (isset($val2['tutor']) and $tutorCount == 0)
                            continue;
                        // 判斷是否為學校模組，有設定 attr 的屬性才判斷
                        if (isset($val2['attr'])) {
                            if (!in_array($session->school['attr_name'], $val2['attr']))
                                continue;
                        }
                        $isAllow2 = false;
                        $badge2 = '';
                        if (isset($val2['numbered']) and $val2['numbered'])
                            $badge2 = '<span class="badge">' . $j++ . '</span> ';

                        if (isset($val2['controller'])) {
                            $resource2 = $namespace . ':' . $val2['controller'];
                            if ($this->_acl->hasResource($namespace))
                                $isAllow2 = $this->_acl->isAllowed($role, $namespace);
                            if ($this->_acl->hasResource($resource2))
                                $isAllow2 = $this->_acl->isAllowed($role, $resource2);
                            if (!$isAllow2)
                                continue;
                        }


                        if ($val2) {
                            $action = isset($val2['action']) ? '/' . $val2['action'] : '';
                            $str .= '<li><a href="/' . $route . '/' . $val2['controller'] . $action . '">' . $badge2 . $val2['label'] . '</a></li>';
                        } else
                            $str .= '<li class="divider"></li>';
                    }
                    $str .= '</ul>';
                } else {
                    if ($val['controller'] == $controllerName)
                        $str .= '<li class="active ' . $class . '">';
                    else
                        $str .= '<li class="' . $class . '">';

                    $str .= '<a href="/' . $route . '/' . $val['controller'] . '">' . $fa_class . $val['label'] . ' ' . $badge . '</a>';
                }

                $str .= '</li>';
                $i++;
            }

        }


        $str .= '</ul>';

        return $str;
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
