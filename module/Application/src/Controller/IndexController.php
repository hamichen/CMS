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
use Zend\Form\Element\Select;
use Zend\Form\Form;
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

    public function testAction()
    {
        $em = $this->getEntityManager();

        $res = $em->getRepository('Base\Entity\SchoolOffice')->findAll();
        $viewModel = new ViewModel();
        $viewModel->setVariable('data', $res);

        return $viewModel;

    }

    public function studentAction()
    {
        $em = $this->getEntityManager();
        $res = $em->getRepository('Base\Entity\Semester')->findAll();

        // 建立學期表單 options
        $arr = [];
        /** @var  $row \Base\Entity\Semester */
        foreach ($res as $row) {
            $id = $row->getId();
            $name = $row->getYear().'學年第'.$row->getSemester().'學期';
            $arr[$id] = $name;
        }

        $form = new Form('student_form');
        $form->add([
            'name' =>'semester_id',
            'type' => Select::class,
            'options' => [
                'label' =>'選擇學期',
                'empty_option' => '選擇學期',
                'value_options' => $arr
            ]
        ]);

        $form->add([
            'name' =>'class_id',
            'type' => Select::class,
            'options' => [
                'label' =>'選擇班級'
            ]
        ]);

        // 如果有 post 傳值
        if ($this->getRequest()->isPost()) {
            // 取出 post 值
            $data = $this->params()->fromPost();

            // 將值設回給 form 做為初值
            $form->setData($data);
            // 取出 學期 id
            $semesterId = $data['semester_id'];
            // 以 Doctrine createQueryBuilder 查詢資料
            $qb = $em->createQueryBuilder()
                ->select('u')
                ->from('Base\Entity\SemesterClass', 'u')
                ->leftJoin('u.semester', 'semester')
                ->where('semester.id=:id')
                ->setParameter('id', $semesterId)
                ->getQuery()
                ->getArrayResult();

            // 建立班級陣列
            $arr = [];
            foreach ($qb as $item) {
                $id = $item['id'];
                $name = $item['grade'].'年'.$item['class_name'].'班 ('.$item['tutor'].')';
                $arr[$id] = $name;
            }
            // 將班級下拉選單加入 options
            $form->get('class_id')->setValueOptions($arr);

//            $res = $em->getRepository('Base\Entity\SemesterClass')
//                ->findBy(['semester_id' => $semesterId]);
        }



        $viewModel = new ViewModel();
        $viewModel->setVariable('studentForm', $form);

        return $viewModel;
    }
}
