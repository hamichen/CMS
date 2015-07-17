<?php
/**
 * Created by PhpStorm.
 * User: hami
 * Date: 2015/7/17
 * Time: 上午 10:46
 */

namespace Base\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\View\HelperPluginManager as ServiceManager;


class Config extends AbstractHelper {

    protected $serviceManager;

    public function __construct(ServiceManager $serviceManager) {
        $this->serviceManager = $serviceManager;
    }

    public function __invoke() {
        $config = $this->serviceManager->getServiceLocator()->get('Config');
        return $config;
    }

}