<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 2019/1/22
 * Time: 上午 10:54
 */

namespace Admin\Controller;


use Application\TcApi\SemesterData;
use Application\TcApi\TcApi;
use Base\Controller\BaseController;

class ApiController extends BaseController
{
    public function indexAction()
    {
        // 取得 serviceManager
        $sm = $this->getServiceManager();
        // 建立 APi 物件
        $api = new SemesterData($sm);

        $api->syncData();
        exit;
    }

}