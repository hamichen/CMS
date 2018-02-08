<?php
namespace Cms\Controller;

use Base\Controller\BaseController;
use Base\Entity\Menu;
use Cms\Form\ModuleForm;

use Cms\Form\MenuFormFIilter;

use Zend\View\Model\JsonModel;

use Cms\Form\MenuForm;

use Zend\View\Model\ViewModel;


class MenuController extends BaseController
{

    public function indexAction()
    {

        $viewModel = new ViewModel();
        $id = (int) $this->params()->fromQuery('id');

        $em = $this->getEntityManager();


        $qb = $em->createQueryBuilder()
            ->select('u.id,u.name, u.order_id, m.id as parent_id, u.url, u.target, u.layout, u.is_display, count(c.id) as cc')
            ->from('Base\Entity\Menu','u')
            ->leftJoin('u.menu','m')
            ->leftJoin('u.menus', 'c')
            ->groupBy('u.id')
            ->orderBy('u.order_id');
        if ($id)
            $qb->where('m.id=:id')
                ->setParameter('id', $id);
        else
            // 預設首頁
            $qb->where('m.id = 1');

        $res = $qb->getQuery()->getArrayResult();
       if (count($res)){
           if ($res[0]['parent_id'] ){
               // 上層選單
               $qb = $em->createQueryBuilder()
                   ->select('m.id')
                   ->from('Base\Entity\Menu', 'u')
                   ->leftJoin('u.menu','m')
                   ->where('u.id=:id')
                   ->setParameter('id',$res[0]['parent_id'])
                   ->getQuery()
                   ->getArrayResult();
               $viewModel->setVariable('parentId', $qb[0]['id']);
           }

           $layoutOptions = $em->getRepository('Base\Entity\Menu')->getLayoutOptions();
           $viewModel->setVariable('layoutName', $layoutOptions);

           $viewModel->setVariable('data', $res);
       }

        return $viewModel;
    }


    public function editAction()
    {
        $viewModel = new ViewModel();
        $em = $this->getEntityManager();
        $menuArr = $em->getRepository('Base\Entity\Menu')->getMenuOptions();
        $layoutOptions = $em->getRepository('Base\Entity\Menu')->getLayoutOptions();

        $form = new MenuForm();
        $form->get('layout')->setValueOptions($layoutOptions);

        if ($id = (int) $this->params()->fromQuery('id')) {
            /** @var  $menuRes \Base\Entity\Menu */
            $menuRes = $em->getRepository('Base\Entity\Menu')->find($id);
            $params = unserialize($menuRes->getParams());
            $form->bind($menuRes);
            if ($id > 1)
            $form->get('parent_id')->setValue($menuRes->getMenu()->getId());
            $form->get('max_records')->setValue($params['max_records']);
            $form->get('order_kind')->setValue($params['order_kind']);
            $form->get('term')->setValue($params['term']);
            if (isset($params['title_limit']))
                $form->get('title_limit')->setValue($params['title_limit']);


            // 上層選單排除自己
            $menuArr[$id]['disabled'] = 'disabled';
            $childIdArr = $em->getRepository('Base\Entity\Menu')->getChildIdArray($id);
            foreach ($childIdArr as $cId)
                $menuArr[$cId]['disabled'] = 'disabled';
        }
        $form->get('parent_id')->setValueOptions($menuArr);
        $viewModel->setVariable('form', $form);
        return $viewModel;
    }

    public function saveAction()
    {
        $jsonModel = new JsonModel();
        $em = $this->getEntityManager();
        $form = new MenuForm();
        $layoutOptions = $em->getRepository('Base\Entity\Menu')->getLayoutOptions();
        $form->get('layout')->setValueOptions($layoutOptions);
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            if ($id = $data['id']) {
                $menuRes = $em->getRepository('Base\Entity\Menu')->find($id);
            }
            else{
                $menuRes = new Menu();
            }

            $form->bind($menuRes);

            $form->setData($data);

            if ($form->isValid()) {
                $params['max_records'] = $data['max_records'];
                $params['order_kind'] = $data['order_kind'];
                $params['term'] = $data['term'];
                $params['title_limit'] = $data['title_limit'];
                if (isset($data['parent_id'])) {
                    $parentRes = $em->getRepository('base\Entity\Menu')->find($data['parent_id']);
                    if (!$orderId  = (int)$data['order_id']) {
                        $orderId = count($parentRes->getMenus())+1;
                    }
                    $menuRes->setMenu($parentRes);
                    $menuRes->setOrderId($orderId);
                }

                $menuRes->setParams(serialize($params));
                /** @var  $user \Base\Entity\User */
                $user = $this->getAuthService()->getIdentity();

                $menuRes->setUpdateTime(new \DateTime());
                $menuRes->setUser($em->getReference('Base\Entity\User', $user->getUsername()));

                $em->persist($menuRes);
                $em->flush();

               $jsonModel->setVariable('success', true);
            }
            else{
                $jsonModel->setVariable('success', false);
                $jsonModel->setVariable('message',$form->getMessages());
            }

        }
        return $jsonModel;
    }


    public function deleteAction()
    {
        $id = (int)$this->params()->fromQuery('id');
        $em = $this->getEntityManager();
        $res = $em->getRepository('Base\Entity\Menu')->find($id);
        $jsonModel = new JsonModel();
        if ($count = count($res->getMenus())) {
            $jsonModel->setVariable('success', false);
            $jsonModel->setVariable('message', '有'.$count.'個子項，不能刪除');
        }
        elseif ($count = count($res->getPages())) {
            $jsonModel->setVariable('success', false);
            $jsonModel->setVariable('message', '有'.$count.'個頁面，不能刪除');
        }
        else {
            $em->remove($res);
            $em->flush();
            $jsonModel->setVariable('success', true);
        }

        return $jsonModel;
    }


    /**
     *  排序
     */
    public function sortMenuAction()
    {
        $jsonModel = new JsonModel();
        $sort = $this->getRequest()->getPost()->get('sort');
        foreach ($sort as $order=>$id) {
            $em = $this->getEntityManager();
            if ($articleKind = $em->getRePository('Menu\Entity\Menu')
                ->findOneBy(array('id'=>$id))) {
                $articleKind->setOrderId($order);
                $em->persist($articleKind);
                $em->flush();
            }
        }
        $this->getServiceLocator()->get('Menu\Model\Menu')->removeCache($this->menuItem->getMenuName());
        $jsonModel->setVariable('success', true);
        return $jsonModel;
    }

    public function deleteMenuAction()
    {
        $jsonModel = new JsonModel();
        $jsonModel->setVariable('success', false);
        if ($id = (int)$this->getEvent()->getRouteMatch()->getParam('id')) {
            $em = $this->getEntityManager();
            if ($articleKind = $em->getRePository('Menu\Entity\Menu')
                ->findOneBy(array('id'=>$id))) {
                $count = count($articleKind->getPages());
                if ($count == 0) {
                    $em->remove($articleKind);
                    $em->flush();
                    $this->getServiceLocator()->get('Menu\Model\Menu')->removeCache($this->menuItem->getMenuName());
                    $jsonModel->setVariable('success', true);
                }
                else {
                    $jsonModel->setVariable('message', '己有'.$count.'筆頁面法刪除');
                    $jsonModel->setVariable('success', false);
                }

            }
        }
        return $jsonModel;
    }

    public function getParentIdOptionAction()
    {
        $jsonModel = new JsonModel();
        // 上層選單目錄
        $options = $this->getServiceLocator()->get('Menu\Model\Menu')->getOptions($this->menuItem->getMenuName());
        $options = array('0'=>'首層') + $options;
        $jsonModel->setVariable('options', $options);
        return $jsonModel;
    }
}
