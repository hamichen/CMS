<?php
/**
 *
 *
 * @author    sfs teams <zfsfs.team@gmail.com>
 * @copyright 2010-2016 (http://www.sfs.tw)
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 * @link      http://www.sfs.tw
 * Date: 2016/9/12
 * Time: 下午 9:17
 */

namespace Base\Service\Factory;


use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;
use Zend\Session\Container;

class SchoolSessionFactory implements FactoryInterface
{

    private $_session;

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $session = new Container('school');

        if (!$school = $session->school)
            return false;
        if (!$this->_session) {
            $this->_session = new Container('school_'.$school['edu_no']);

        }
        return $this->_session;
    }
}