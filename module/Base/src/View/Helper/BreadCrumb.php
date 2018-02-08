<?php
namespace Base\View\Helper;

use Interop\Container\ContainerInterface;
use Zend\View\Helper\AbstractHelper;


class BreadCrumb extends AbstractHelper
{

    protected $container;

    public function __invoke()
    {
        /** @var \Zend\Mvc\Console\Router\SimpleRouteStack $router */
        $router = $this->container->get('router');

        /** @var \Zend\Http\PhpEnvironment\Request $request */
        $request = $this->container->get('request');
        if (!$routeMatch = $router->match($request))
            return false;

        $config = $this->container->get('config');
        //取得 request 的 namespace
        $module = $routeMatch->getParam('module');
        $moduleName = strtolower($module);
        if ($moduleName == 'application') return '';

        $controllerName = $routeMatch->getParam('controller');

        $tempArr = explode('Controller\\', $controllerName);
        $tempStr = str_replace('Controller','',end($tempArr));
        $arr = preg_split('#([A-Z][^A-Z]*)#', $tempStr, null, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
        $controllerName = strtolower(implode('-', $arr));
        $res = [];
        $res[0]['url'] = $config['router']['routes'][$moduleName]['options']['route'];
        $res[0]['name'] = $config['router']['routes'][$moduleName]['options']['name'];
        if (isset($config['router']['routes'][$moduleName]['child_routes'][$controllerName])) {
            $res[1]['url'] = '/'.$moduleName.'/'.$controllerName;
            $res[1]['name'] = $config['router']['routes'][$moduleName]['child_routes'][$controllerName]['options']['name'];
        }
        return $res;
    }

    /**
     * School constructor.
     * @param ContainerInterface $container
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

}