<?php
namespace Base\View\Helper;

use Interop\Container\ContainerInterface;
use Zend\View\Helper\AbstractHelper;


class ModuleTitle extends AbstractHelper
{

    protected $container;

    public function __invoke($delimiter='')
    {
        $router = $this->container->get('router');

        /** @var \Zend\Http\PhpEnvironment\Request $request */
        $request = $this->container->get('request');
        /** @var  $routeMatch \Zend\Router\Http\RouteMatch */
        $routeMatch = $router->match($request);
        if (!$routeMatch) return;
        $routName = $routeMatch->getMatchedRouteName();
        $tempArr = explode('/',$routName);
        if (count($tempArr)>1) {
            $config = $this->container->get('config');
            if (isset($config['router']['routes'][$tempArr[0]]['child_routes'][$tempArr[1]]['options']['name']))
                return $delimiter.$config['router']['routes'][$tempArr[0]]['child_routes'][$tempArr[1]]['options']['name'];
        }
        return '';
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