<?php
/**
 *
 *
 * @author    sfs teams <zfsfs.team@gmail.com>
 * @copyright 2010-2017 (http://www.sfs.tw)
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 * @link      http://www.sfs.tw
 * Date: 2017/5/23
 * Time: 上午 9:16
 */

namespace Base\Middleware\Factory;


use Base\Middleware\AuthorizationMiddleware;
use Interop\Container\ContainerInterface;
use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\Factory\FactoryInterface;

class AuthorizationMiddlewareFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {

        $authService = $container->get(AuthenticationService::class);
        $acl = $container->get('Acl');
        $response = $container->get('Response');
        $baseUrl = $container->get('Request')->getBaseUrl();
        $authorization = new AuthorizationMiddleware($authService, $acl, $response, $baseUrl);
        return $authorization ;


    }
}