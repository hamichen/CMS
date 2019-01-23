<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 2019/1/23
 * Time: 下午 01:07
 */

namespace Exam\Controller;


use Base\Controller\BaseController;
use Base\Entity\Exam;
use Exam\Form\ExamForm;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class AdminController extends BaseController
{

    public function indexAction()
    {
        $em = $this->getEntityManager();
        $res = $em->getRepository('Base\Entity\Exam')->findAll();

        $viewModel = new ViewModel();
        $viewModel->setVariable('data',$res);
        return $viewModel;
    }

    public function addAction()
    {
        $examForm = new ExamForm('exam_form');
        $em = $this->getEntityManager();
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();

            $examForm->setData($data);
            if ($examForm->isValid()) {
                /** @var  $user \Base\Entity\User */
                $user = $this->getAuthService()->getIdentity();


                $data2 = $examForm->getData();

                if ($data['id']) {
                    $exam = $em->getRepository('Base\Entity\Exam')->find($data['id']);
                }
                else
                    $exam = new Exam();

                $exam->setSubject($data2['subject']);
                $exam->setCreateTime(new \DateTime());
                $exam->setMemo($data2['memo']);
                $exam->setTeacher($user->getTeacher());

                if ($data2['open_time']) {
                    $exam->setOpenTime(new \DateTime($data2['open_time']));
                }
                if ($data2['close_time']) {
                    $exam->setCloseTime(new \DateTime($data2['close_time']));
                }
                $em->persist($exam);
                $em->flush();
                return $this->redirect()->toUrl('/exam/admin');
            }
        }

        $id = $this->params()->fromQuery('id', 0);
        if ($id) {
            /** @var  $res \Base\Entity\Exam */
            $res = $em->getRepository('Base\Entity\Exam')->find($id);
            if ($res) {
                $examForm->bind($res);
                if ($res->getOpenTime())
                    $examForm->get('open_time')->setValue($res->getOpenTime()->format('Y-m-d H:i'));
                if ($res->getCloseTime())
                    $examForm->get('close_time')->setValue($res->getCloseTime()->format('Y-m-d H:i'));
            }
        }


        $viewModel = new ViewModel();
        $viewModel->setVariable('examForm', $examForm);

        return $viewModel;
    }


    public function deleteAction()
    {
        $id = $this->params()->fromQuery('id');

        $em = $this->getEntityManager();

        $res = $em->getRepository('Base\Entity\Exam')->find($id);

        $jsonModel = new JsonModel();
        if ($res) {
            $em->remove($res);
            $em->flush();
            $jsonModel->setVariable('success', true);
        }

        return $jsonModel;
    }
}