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
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Zend\Filter\FilterChain;
use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;

class FileController extends BaseController
{
    public function indexAction()
    {
        $viewModel = new ViewModel();
        $em = $this->getEntityManager();

        /** @var  $menuRes \Base\Entity\MenuRepository */
        $menuRes = $em->getRepository('Base\Entity\Menu');
        $menuData = $menuRes->createQueryBuilder('u')
            ->select('u')
            ->where('u.name=:name')
            ->setParameter('name', '檔案文件區')
            ->getQuery()
            ->getOneOrNullResult();


        $params = unserialize($menuData->getParams());

        $qb = $em->createQueryBuilder()
            ->select('u, f, user, tag')
            ->from('Base\Entity\Page', 'u')
            ->leftJoin('u.menu', 'm')
            ->leftJoin('u.user', 'user')
            ->leftJoin('u.pageFiles', 'f')
            ->leftJoin('u.pageTags', 'tag')
            ->where('m.id=:id')
            ->andWhere('u.is_published = 1')
            ->setParameter('id', $menuData->getId());
        if ($term = $this->params()->fromRoute('term')) {
            $filterChain = new FilterChain();
            $filterChain->attach(new StripTags())->attach(new StringTrim());
            $term = $filterChain->filter($term);
            $qb->andWhere('u.term=:term')
                ->setParameter('term', $term);
        }

        switch ($params['order_kind']) {
            case 'desc' :
                $qb->orderBy('u.create_time', 'desc');
                break;
            case 'asc' :
                $qb->orderBy('u.create_time', 'asc');
                break;
            case 'id_desc' :
                $qb->orderBy('u.id', 'desc');
                break;
            case 'id_asc' :
                $qb->orderBy('u.id', 'asc');
                break;
            case 'custom_desc' :
                $qb->orderBy('u.order_id', 'desc');
                break;
            case 'custom_asc' :
                $qb->orderBy('u.order_id', 'asc');
                break;
        }


        $adapter = new DoctrineAdapter(new ORMPaginator($qb));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage($params['max_records']);

        if ($page = $this->params()->fromRoute('page'))
            $paginator->setCurrentPageNumber($page);


        $viewModel->setVariable('menuData', $menuData);
        $viewModel->setVariable('paginator', $paginator);

        /*if ($params['term'])
            $viewModel->setVariable('termArr', $this->_getBlogTerm($menuData->getId(), $params['term']));*/

      //  $viewModel->setTemplate('application/menu/blog.twig');
        return $viewModel;

    }



    /**
     * 下載附件
     */
    public function getFileAction()
    {
        $id = $this->params()->fromRoute('id');
        /** @var \Zend\Http\Response $tmpResponse */
        $tmpResponse = $this->getResponse();
        $dm = $this->getDocumentManager();
        if ($data = $dm->createQueryBuilder('Base\Document\CmsFile')
            ->field('id')->equals($id)
            ->getQuery()
            ->getSingleResult()
        ) {
            $em = $this->getEntityManager();
            /** @var  $res \Base\Entity\PageFile*/
            $res = $em->getRepository('Base\Entity\PageFile')->findOneBy(['file_name'=>$id]);
            $page = $res->getPage();
            $hits = (int)$page->getHits()+1;
            $page->setHits($hits);
            $em->persist($page);
            $em->flush();

            $tmpResponse->getHeaders()->addHeaderLine('Content-Type', $data->getType())
                ->addHeaderLine('Content-Disposition', 'attachment; filename="' . $data->getName() . '"')
                ->addHeaderLine('Content-Length', $data->getLength());
            $tmpResponse->setContent($data->getFile()->getBytes());
            return $tmpResponse;
        } else {
            return $tmpResponse->setStatusCode(404);
        }
    }

}
