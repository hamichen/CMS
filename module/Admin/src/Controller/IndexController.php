<?php
namespace Admin\Controller;

use Admin\Form\ResourceFormInputFilter;

use Admin\Form\ResourceForm;

use Base\Controller\BaseController;
use Zend\View\Model\ViewModel;

use Zend\Mvc\Controller\AbstractActionController;

class IndexController extends BaseController
{

    public function indexAction()
    {
       return  $this->redirect()->toRoute('admin/user');
    }


}