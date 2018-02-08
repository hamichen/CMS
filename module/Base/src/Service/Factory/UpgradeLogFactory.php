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

class UpgradeLogFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $logPath = './data/log';
        if (!is_dir($logPath)){
            mkdir($logPath, 0777, true);
        }
        $log = new \Zend\Log\Logger();
        $writer = new \Zend\Log\Writer\Stream($logPath.'/'.'upgrade.log');
        $log->addWriter($writer);
        return $log;
    }
}