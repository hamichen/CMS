<?php
namespace Base\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class Aclplugin extends AbstractPlugin
{
    /**
     * @param $e \Zend\Mvc\MvcEvent
     */
    public function doAuthorization($e)
    {
        $application = $e->getApplication();
        $sm = $application->getServiceManager();

        /** @var  $auth \Zend\Authentication\AuthenticationService */
        $auth = $sm->get ( 'doctrine.authenticationservice.orm_default' );

        /** @var  $acl \Zend\Permissions\Acl\Acl */
        $acl = $sm->get('acl');
        $role = (! $auth->hasIdentity() or !$auth->getIdentity()) ? 'guest' : $auth->getIdentity()->getRole();


        $routeMatch = $e->getRouteMatch();

        $params = $routeMatch->getParams();
        $resource = strtolower($params['controller']);
        $tempArr = explode('\\',$resource);
        if (count($tempArr) > 1) {
            $resource = str_replace ( 'controller', '', $tempArr[2]);
        }
        else
            $resource = $tempArr[0];


        $module = strtolower($params['module']);
        $resource = $module.':'.$resource;
        $action = $params['action'];

        $isAllow = false;
        if ($acl->hasResource ( $module )){
            $isAllow = $acl->isAllowed ( $role, $module, $action );
        }


        if (!$isAllow){
            if ($acl->hasResource ( $resource )){
                $isAllow = $acl->isAllowed ( $role, $resource, $action );
            }
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
