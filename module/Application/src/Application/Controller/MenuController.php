<?php

namespace Application\Controller;


use Base\Controller\BaseController;
use Zend\View\Model\ViewModel;

class MenuController extends BaseController
{
    public function indexAction()
    {
        $viewModel = new ViewModel();
        return $viewModel;
    }

}