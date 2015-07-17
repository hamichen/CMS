<?php
/**
 * Created by PhpStorm.
 * User: hami
 * Date: 2015/7/12
 * Time: 下午 04:21
 */

namespace Base\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
class UserIdentity extends AbstractPlugin
{

    /**
     *
     * @return \Base\Entity\user
     *
     */
    public function __invoke()
    {

        $controller = $this->getController();

        $auth = $controller->getServiceLocator()->get ( 'doctrine.authenticationservice.orm_default' );
        if (! $auth->getIdentity() instanceof \Base\Entity\User)
            throw new \Exception('錯誤');

        return $auth->getIdentity();

    }
}