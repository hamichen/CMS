<?php

namespace Cms\Controller;

use Base\Controller\BaseController;
use Zend\View\Model\ViewModel;

class MenuController extends BaseController
{

    public function indexAction()
    {

        return new ViewModel();
    }

}

