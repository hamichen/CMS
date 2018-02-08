<?php
/**
 *
 *
 * @author    sfs teams <zfsfs.team@gmail.com>
 * @copyright 2010-2016 (http://www.sfs.tw)
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 * @link      http://www.sfs.tw
 * Date: 2016/9/11
 * Time: 下午 11:07
 */

namespace Base\View\Helper\Service;


use Interop\Container\ContainerInterface;
use Base\View\Helper\UserIdentity;
use Zend\ServiceManager\Factory\FactoryInterface;

class UserIdentityFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $helper = new UserIdentity();
        if ($container->has('doctrine.authenticationservice.orm_default')) {
            $helper->setAuthenticationService($container->get('doctrine.authenticationservice.orm_default'));
        }
        return $helper;
    }

}