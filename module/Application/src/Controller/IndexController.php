<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Base\Controller\BaseController;
use Zend\View\Model\ViewModel;
use ApigilityConsumer\Service\ClientAuthService;
use ApigilityConsumer\Service\ClientService;

class IndexController extends BaseController
{
    public function indexAction()
    {
        $viewModel = new ViewModel();

        $em = $this->getEntityManager();
        $res = $em->getRepository('Base\Entity\Page')->findAll();

        $viewModel->setVariable('data', $res);

        return $viewModel;
    }

}
