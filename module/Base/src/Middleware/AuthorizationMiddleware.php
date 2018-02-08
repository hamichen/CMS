<?php
/**
 *
 *
 * @author    sfs teams <zfsfs.team@gmail.com>
 * @copyright 2010-2017 (http://www.sfs.tw)
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 * @link      http://www.sfs.tw
 * Date: 2017/5/22
 * Time: 下午 9:43
 */

namespace Base\Middleware;

use Zend\Authentication\AuthenticationService;
use Zend\Http\PhpEnvironment\Response;
use Zend\Permissions\Acl\Acl;
use Zend\Router\RouteMatch;

class AuthorizationMiddleware
{
    private $authService ;
    private $acl;
    private $response;
    private $baseUrl;

    /**
     * AuthorizationMiddleware constructor.
     * @param AuthenticationService $authService
     * @param Acl $acl
     * @param Response $response
     * @param $baseUrl
     */
    public function __construct(AuthenticationService $authService, Acl $acl, Response $response, $baseUrl)
    {
        $this->authService = $authService;
        $this->acl = $acl;
        $this->response = $response;
        $this->baseUrl = $baseUrl;
    }

    public function checkProtectedRoutes(RouteMatch $match)
    {
        if (!$match) {
            // Nothing without a route
            return null;
        }
        // Do your checks...
    }
}