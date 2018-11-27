<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 2018/11/27
 * Time: 下午 01:52
 */

namespace Admin\Controller;


use Admin\Form\NoteForm;
use Base\Controller\BaseController;
use Base\Entity\Note;
use Zend\View\Model\ViewModel;

class NoteController extends BaseController
{

    public function indexAction()
    {
        $em = $this->getEntityManager();

//        $res = $em->getRepository('Base\Entity\Note')->findAll();
        $res = $em->createQueryBuilder()
        ->select('u')
        ->from('Base\Entity\Note', 'u');
        if ($sort = $this->params()->fromQuery('sort')) {
            switch ($sort) {
                case 'title':
                    $res->orderBy('u.title');
                    break;
                case 'time' :
                    $res->orderBy('u.create_time');
                    break;
            }
        }

        $q = $res->getQuery()->getResult();

        $viewModel = new ViewModel();

        $viewModel->setVariable('data', $q);

        return $viewModel;


    }

    public function addAction()
    {
        $form = new NoteForm('note_form');

        if ($this->request->isPost()) {
            $data = $this->params()->fromPost();
            $em = $this->getEntityManager();
            $note = new Note();
            $note->setTitle($data['title']);
            $note->setContent($data['content']);
            $note->setCreateTime(new \DateTime());
            /** @var  $user \Base\Entity\User */
            $user = $this->getAuthService()->getIdentity();
            $note->setUser($user);

            $em->persist($note);
            $em->flush();

            return $this->redirect()->toUrl('/admin/note/index');


        }


        $viewModel = new ViewModel();

        $viewModel->setVariable('nodeForm', $form);

        return $viewModel;

    }


    public function deleteAction()
    {
        $id = (int) $this->params()->fromQuery('id');
        $em = $this->getEntityManager();
        $res = $em->getRepository('Base\Entity\Note')->find($id);
        if ($res) {
            $em->remove($res);
            $em->flush();
            return $this->redirect()->toUrl('/admin/note/index');
        }

        return $this->redirect()->toUrl('/admin/note');


    }

    public function editAction()
    {
        $em = $this->getEntityManager();
        if ($this->request->isPost()) {
            $id = (int) $this->params()->fromPost('id');
            /** @var  $res \Base\Entity\Note */
            $res = $em->getRepository('Base\Entity\Note')->find($id);
            $data = $this->params()->fromPost();
            if ($res) {
                $res->setTitle($data['title']);
                $res->setContent($data['content']);
                $res->setCreateTime(new \DateTime());
                $em->persist($res);

                $em->flush();
                return $this->redirect()->toUrl('/admin/note/index');
            }

        }

        $id = (int) $this->params()->fromQuery('id');
        $res = $em->getRepository('Base\Entity\Note')->find($id);
        $viewModel = new ViewModel();
        if ($res) {
            $noteForm = new NoteForm('note_form');
            $noteForm->bind($res);

            $viewModel->setVariable('nodeForm', $noteForm);
        }
        $viewModel->setTemplate('/admin/note/add.twig');

        return $viewModel;


    }

}