<?php
namespace Base\View\Helper;

use Interop\Container\ContainerInterface;
use Zend\View\Helper\AbstractHelper;


class ActivePage extends AbstractHelper
{

    protected $container;

    public function __invoke()
    {
        $router = $this->container->get('router');

        /** @var \Zend\Http\PhpEnvironment\Request $request */
        $request = $this->container->get('request');
        /** @var  $routeMatch \Zend\Router\Http\RouteMatch */
        $routeMatch = $router->match($request);
        $routName = $routeMatch->getMatchedRouteName();
        return $routName;
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