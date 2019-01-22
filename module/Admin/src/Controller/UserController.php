<?php

namespace Admin\Controller;


use Admin\Form\UserForm;
use Base\Controller\BaseController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

class UserController extends BaseController
{

    public function indexAction()
    {
        $em = $this->getEntityManager();
        $roleArr = $em->getRepository('Base\Entity\User')->getRoleArray();
        $viewModel = new ViewModel();

        $qb = $em->createQueryBuilder()
            ->select('u')
            ->from('Base\Entity\User', 'u')
            ->getQuery()
            ->getArrayResult();

        $viewModel->setVariable('data', $qb);
        $viewModel->setVariable('roleArr', $roleArr);
        return $viewModel;

    }

    public function editAction()
    {
        $form = new UserForm();
        $em = $this->getEntityManager();
        $roleArr = $em->getRepository('Base\Entity\User')->getRoleArray();
        $form->get('role')->setValueOptions($roleArr);


        if ($id = $this->params()->fromQuery('id')) {
            $em = $this->getEntityManager();
            $res = $em->getRepository('Base\Entity\User')->find($id);
            $form->bind($res);
        }

        $viewModel = new ViewModel();

        $viewModel->setVariable('form', $form);
        return $viewModel;
    }

    public function saveAction()
    {
        $jsonModel = new JsonModel();
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $em = $this->getEntityManager();
            if (!$userRes = $em->getRepository('Base\Entity\User')->find($data['id']))
                $userRes = new \Base\Entity\User();

            $form = new UserForm();
            $roleArr = $em->getRepository('Base\Entity\User')->getRoleArray();
            $form->get('role')->setValueOptions($roleArr);

            $form->setData($data);
            if ($form->isValid()) {
                $userRes->setDisplayName($data['display_name']);
                $userRes->setUsername($data['username']);
                $userRes->setRole($data['role']);
                $userRes->setPassword(md5($data['password']));
                $em->persist($userRes);
                $em->flush();
                $jsonModel->setVariable('success', true);
            } else {
                $jsonModel->setVariable('success', false);
                $jsonModel->setVariable('message', $form->getMessages());
            }
        }

        return $jsonModel;
    }

    public function deleteAction()
    {
        $id = $this->params()->fromQuery('id');
        $em = $this->getEntityManager();
        $jsonModel = new JsonModel();
        /** @var  $res \Base\Entity\Competition */
        $res = $em->getRepository('Base\Entity\User')->find($id);

        $em->remove($res);
        $em->flush();
        $jsonModel->setVariable('success', true);


        return $jsonModel;
    }
}
