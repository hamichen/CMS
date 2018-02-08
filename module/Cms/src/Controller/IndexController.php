<?php

namespace Cms\Controller;

use Base\Controller\BaseController;
use Zend\View\Model\ViewModel;

class IndexController extends BaseController
{

    public function indexAction()
    {

        return new ViewModel();
    }


}

