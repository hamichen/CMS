<?php

namespace Application\Controller;


use Base\Controller\BaseController;
use Zend\View\Model\ViewModel;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;


class MenuController extends BaseController
{
    public function indexAction()
    {
        $viewModel = new ViewModel();
        $em = $this->getEntityManager();
        $id = $this->params()->fromRoute('id', 1);

        /** @var  $menuRes \Base\Entity\MenuRepository */
        $menuRes = $em->getRepository('Base\Entity\Menu');

        $menuData = $menuRes->find($id);
        $params = unserialize($menuData->getParams());

        $qb = $em->createQueryBuilder()
            ->select('u, f, user')
            ->from('Base\Entity\page', 'u')
            ->leftJoin('u.menu', 'm')
            ->leftJoin('u.user', 'user')
            ->leftJoin('u.pageFiles', 'f')
            ->where('m.id=:id')
            ->setParameter('id', $id);

        switch ($params['order_kind']){
            case 'desc' :
                $qb->orderBy('u.create_time','desc');
                break;
            case 'asc' :
                $qb->orderBy('u.create_time','asc');
                break;
            case 'id_desc' :
                $qb->orderBy('u.id','desc');
                break;
            case 'id_asc' :
                $qb->orderBy('u.id','asc');
                break;
            case 'custom_desc' :
                $qb->orderBy('u.order_id','desc');
                break;
            case 'custom_asc' :
                $qb->orderBy('u.order_id','asc');
                break;
        }

        $adapter = new DoctrineAdapter(new ORMPaginator($qb));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage($params['max_records']);

        if ($page = $this->params()->fromRoute('page'))
            $paginator->setCurrentPageNumber($page);


        $viewModel->setVariable('paginator', $paginator);


        return $viewModel;
    }

    public function downloadAction()
    {
        $id = (int)$this->params()->fromQuery('id');
        $em = $this->getEntityManager();
        /** @var  $res \base\Entity\PageFile */
        $res = $em->getRepository('Base\Entity\PageFile')->find($id);
        if ($res) {
            $filePath  = $this->FileStorePath('cms');

            return $this->Download($filePath.'/'.$res->getFileName(), $res->getSourceName());
        }
        else
            throw new \Exception('錯誤的檔案');
    }
}