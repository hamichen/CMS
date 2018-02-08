<?php
/**
 *
 *
 * @author    sfs teams <zfsfs.team@gmail.com>
 * @copyright 2010-2018 (http://www.sfs.tw)
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 * @link      http://www.sfs.tw
 * Date: 2018/1/6
 * Time: 下午 8:19
 */

namespace Base\Factory;

use Base\Adapter\ApigilityAuthenticationAdapter;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session;

class AuthenticationServiceFactory
{
    public function __invoke($container)
    {
        $adapter = $container->get(ApigilityAuthenticationAdapter::class);

        return new AuthenticationService(
            new Session(), // or your own storage implementing  Zend\Authentication\Storage\StorageInterface
            $adapter
        );
    }

}