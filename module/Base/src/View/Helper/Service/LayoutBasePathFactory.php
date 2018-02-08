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


use Base\View\Helper\LayoutBasePath;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class LayoutBasePathFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $helper = new LayoutBasePath();

        $request = $container->get('request');
        $config = $container->get('config');
        $path = $request->getBasePath() . '/' . $config['layoutPath'];
        $helper->setPath($path);
        return $helper;

    }

}