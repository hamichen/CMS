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
use Zend\View\Model\JsonModel;

class ApiController extends BaseController
{
    public function indexAction()
    {

    }

    public function syncSemesterAction()
    {
        // 取得 serviceManager
        $sm = $this->getServiceManager();
        // 建立 APi 物件
        $api = new SemesterData($sm);
        $jsonModel = new JsonModel();
        try {
            $api->syncData();
            $jsonModel->setVariable('success', true);
        }
        catch (\Exception $e) {
            $jsonModel->setVariable('success', false);
            $jsonModel->setVariable('message', $e->getMessage());
        }

        return $jsonModel;
    }

}