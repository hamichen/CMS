<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 2019/1/23
 * Time: 下午 01:07
 */

namespace Exam\Controller;


use Base\Controller\BaseController;
use Exam\Form\ExamForm;
use Zend\View\Model\ViewModel;

class AdminController extends BaseController
{

    public function indexAction()
    {
        $examForm = new ExamForm('exam_form');

        $viewModel = new ViewModel();
        $viewModel->setVariable('examForm', $examForm);

        return $viewModel;
    }
}