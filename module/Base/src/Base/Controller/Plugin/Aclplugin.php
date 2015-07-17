<?php
namespace Base\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin,
    Zend\Session\Container as SessionContainer,
//    Zend\Permissions\Acl\Acl,
    Zend\Permissions\Acl\Role\GenericRole as Role,
    Zend\Permissions\Acl\Resource\GenericResource as Resource;
//Hamibase\Acl\Acl as Acl;

class Aclplugin extends AbstractPlugin
{
    public function doAuthorization($e)
    {

        /** @var  $auth \Zend\Authentication\AuthenticationService */
        $auth = $e->getApplication ()->getServiceManager ()->get ( 'doctrine.authenticationservice.orm_default' );

        $acl = $e->getApplication()->getServiceManager()->get('acl');


        $role = (! $auth->hasIdentity()) ? 'guest' : $auth->getIdentity()->getRole();

        $routeMatch = $e->getRouteMatch();

        $resource = str_replace ( '\Controller\\', ':', $routeMatch->getParam ( 'controller', 'NA' ) );

        $tempArr =  explode ( ":", $resource );
        $namespace = $tempArr[0];
        if (isset($tempArr[1]))
            $controller  = $tempArr[1];
        else
            $controller = '';

        $namespace = preg_replace('/(?<!^)([A-Z])/', '-\\1', $namespace);
        $namespace = strtolower($namespace);
        $arr = preg_split('#([A-Z][^A-Z]*)#', $controller, null, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
        $resource = $namespace.':'.strtolower(implode('-', $arr));

        $action = $e->getRouteMatch ()->getParam ( 'action', 'NA' );
        $isAllow = false;
        if ($acl->hasResource ( $namespace ))
            $isAllow = $acl->isAllowed ( $role, $namespace, $action );

        if (!$isAllow){
            if ($acl->hasResource ( $resource ))
                $isAllow = $acl->isAllowed ( $role, $resource, $action );
        }


        if (! $isAllow) {

            $ss = substr ( $e->getRequest ()->getRequestUri (), strlen ( $e->getRequest ()->getBaseUrl () ) + 1 );
            $ap = str_replace ( '/', ':', $ss );
            $router = $e->getRouter ();

            $url = $router->assemble ( array (), array (
                'name' => 'user/login'
            ) );

            if ($ap)
                $url .= '/'.$ap;
            $response = $e->getResponse ();
            $response->setStatusCode ( 302 );
            $response->getHeaders ()->addHeaderLine ( 'Location', $url );
            $e->stopPropagation();
        }
    }
}