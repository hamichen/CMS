<?php
/**
 * Created by PhpStorm.
 * User: hami
 * Date: 2015/7/12
 * Time: 下午 04:21
 */

namespace Base\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class FileStorePath extends AbstractPlugin
{


    public function __invoke($path='')
    {
        $controller = $this->getController();
        $arr = explode('\\', get_class($controller));
        $data_path = getcwd() . '/data/files/' . strtolower($arr[0]) ;
        if ($path)
            $data_path .= '/'.$path;

        if (!is_dir($data_path))
            mkdir($data_path, 0755, true);
        return $data_path;
    }
}