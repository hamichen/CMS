<?php
namespace Base\View\Helper;

use Interop\Container\ContainerInterface;
use Zend\View\Helper\AbstractHelper;


class LeftMenu extends AbstractHelper
{

    protected $container;


    public function __invoke($role)
    {
        $router = $this->container->get('router');

        /** @var \Zend\Http\PhpEnvironment\Request $request */
        $request = $this->container->get('request');
        /** @var  $routeMatch \Zend\Router\Http\RouteMatch */
        $routeMatch = $router->match($request);
        if (!$routeMatch) return;
        $routeName = $routeMatch->getMatchedRouteName();

        $config = $this->container->get('config');
        if (!isset($config['acl']['pages'][$role])) return '';

        $menu = $config['acl']['pages'][$role];
        $result = [];
        foreach ($menu as $key=>$val) {
            if (isset($val['pages'])){
                $temp = $this->_checkChild($val['pages'], $routeName);
                $val['is_active'] = $temp['is_active'];
                $val['pages'] = $temp['pages'];
                $result[$key] = $val;
            }
            else{
                if ($val['router'] == $routeName)
                    $val['is_active'] = 1;

                $result[$key] = $val;
            }

        }

        return $result;
    }


    protected function _checkChild($menu, $routeName)
    {
        $result['is_active'] = 0;
        foreach ($menu as $key=>$val) {
            if ($val['router'] == $routeName) {
                $val['is_active'] = 1;
                $result['is_active'] = 1;
            }
            $result['pages'][$key] = $val;
        }

        return $result;
    }


    /**
     * School constructor.
     * @param ContainerInterface $container
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container= $container;
    }

}