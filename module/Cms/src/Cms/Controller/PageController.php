<?php

namespace Cms\Controller;

use Base\Controller\BaseController;
use Base\Entity\Page;
use Cms\Form\PageFileForm;
use Cms\Form\PageUrlForm;
use Cms\Form\SelectPageForm;
use Cms\Form\PageTextForm;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;

class PageController extends BaseController
{

    public function indexAction()
    {
       // $this->FileStorePath();
        $viewModel = new ViewModel();
        $em = $this->getEntityManager();
        $pageForm = new SelectPageForm();
        /** @var  $menuRes \Base\Entity\MenuRepository */
        $menuRes = $em->getRepository('Base\Entity\Menu');
        $menuOptions = $menuRes->getMenuOptions(1);
        $pageForm->get('menu_id')->setValueOptions($menuOptions);

        if (!$id = (int) $this->params()->fromQuery('menu_id')){
            $id = current(array_keys($menuOptions));
        }
        else {
            $pageForm->get('menu_id')->setValue($id);
        }

        $menuData = $menuRes->find($id);
        $params = unserialize($menuData->getParams());

        $qb = $em->createQueryBuilder()
            ->select('u, f')
            ->from('Base\Entity\page', 'u')
            ->leftJoin('u.menu', 'm')
            ->leftJoin('u.pageFiles', 'f')
            ->where('m.id=:id')
            ->setParameter('id', $id);

        switch ($params['order_kind']){
            case 'desc' :
                $qb->orderBy('u.update_time','desc');
                break;
            case 'asc' :
                $qb->orderBy('u.update_time','asc');
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

        $pageKindArray = $em->getRepository('base\Entity\Page')->getPageKindArray();

        $viewModel->setVariable('paginator', $paginator);
        $viewModel->setVariable('pagekindArray', $pageKindArray);

        $viewModel->setVariable('form', $pageForm);


        return $viewModel;
    }



    public function getPageAction()
    {
        $viewModel = new ViewModel();
        $em = $this->getEntityManager();
        $kind = $this->params()->fromQuery('kind');
        $orderId = null;
        if ($id = (int) $this->params()->fromQuery('id')) {
            $pageRes = $em->getRepository('Base\Entity\Page')->find($id);
            $kind = $pageRes->getKind();
        }
        else{
            $pageRes = new \Base\Entity\Page();
            // 最後一篇文章
            $qb = $em->createQueryBuilder()
                ->select('count(u.id) as cc')
                ->from('Base\Entity\Page', 'u')
                ->leftJoin('u.menu','m')
                ->where('m.id=:id')
                ->setParameter('id', $this->params()->fromQuery('menu_id'))
                ->getQuery()
                ->getArrayResult();
            $orderId = $qb[0]['cc'] +1;
        }




        switch ($kind) {
            case 'text':
                $form = new PageTextForm();

                $form->bind($pageRes);
                if ($orderId)
                    $form->get('order_id')->setValue($orderId);
                $viewModel->setTemplate('cms/page/page-text.twig');

                break;
            case 'file':
                $form = new PageFileForm();

                $form->bind($pageRes);
                if ($orderId)
                    $form->get('order_id')->setValue($orderId);
                $viewModel->setVariable('file_store_path', $this->FileStorePath());
                $viewModel->setVariable('files', $pageRes->getPageFiles());
                $viewModel->setTemplate('cms/page/page-file.twig');

                break;
            case 'url' :
                $form = new PageUrlForm();

                $form->bind($pageRes);
                if ($orderId)
                    $form->get('order_id')->setValue($orderId);
                $viewModel->setTemplate('cms/page/page-url.twig');

                break;
        }
        $viewModel->setVariable('form',$form);
        return $viewModel;
    }

    public function deleteAction()
    {
        $jsonModel = new JsonModel();
        $em = $this->getEntityManager();
        if ($id = (int)$this->params()->fromQuery('id')) {
            /** @var  $res \Base\Entity\Page */
            $res = $em->getRepository('base\Entity\Page')->find($id);
            // tag 刪除
            $res->getPageTags()->clear();

            //  檔案刪除
            if ($res->getKind() == 'file') {
                /** @var  $file \Base\Entity\PageFile */
                foreach ($res->getPageFiles() as $file){
                    $fileName = $file->getFileName();
                    $filePath = $this->FileStorePath().'/'.$fileName;
                   // ECHO $filePath; exit;
                    if (is_file($filePath))
                        unlink($filePath);
                    $em->remove($file);
                }

            }
            $em->remove($res);
            $em->flush();
            $jsonModel->setVariable('success', true);
        }

        return $jsonModel;
    }

    public function saveAction()
    {
       
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $em = $this->getEntityManager();

            if ($id = $data['id']) {
                /** @var  $pageRes \Base\Entity\Page */
                $pageRes = $em->getRepository('Base\Entity\Page')->find($id);
                $pageRes->setUpdateTime(new \DateTime());

            }
            else{
                $pageRes = new Page();
                $pageRes->setCreateTime(new \DateTime());

            }
            $pageRes->setMenu($em->getReference('Base\Entity\Menu', $data['menu_id']));
            $pageRes->setUser($em->getReference('Base\Entity\user', $this->UserIdentity()->getId()));
            $pageRes->setKind($data['kind']);

           
            switch ($data['kind']) {
                case 'text':
                    return $this->saveText($data, $pageRes);
                    break;

                case 'file' :
                    return $this->saveFile($data, $pageRes);

                    break;
                case 'url' :
                    return $this->saveUrl($data, $pageRes);

                    break;

            }


        }

    }


    /**
     * 文章編修
     * @param $data
     * @param $pageRes  \Base\Entity\Page
     * @return JsonModel
     * @throws \Doctrine\ORM\ORMException
     */
    public function saveText($data, $pageRes)
    {
        $jsonModel = new JsonModel();
        $em = $this->getEntityManager();

        $form = new PageTextForm();
        $form->bind($pageRes);
        $form->setData($data);
        if ($form->isValid()) {


            $em->persist($pageRes);
            $em->flush();
            // tags
            if ($this->params()->fromPost('tags'))
                $this->setTags($data, $pageRes);


            $jsonModel->setVariable('success', true);
        }
        else {
            $jsonModel->setVariable('success', false);
            $jsonModel->setVariable('message', $form->getMessages());
        }

        return $jsonModel;
    }

    /**
     * 檔案上傳
     * @param $data
     * @param $pageRes \Base\Entity\Page
     * @return JsonModel
     */
    public function saveFile($data, $pageRes)
    {
        $jsonModel = new JsonModel();
        $em = $this->getEntityManager();

        $form = new PageFileForm();
        $form->bind($pageRes);
        $form->setData($data);
        if ($form->isValid()) {

            $adapter = new \Zend\File\Transfer\Adapter\Http();

            if (!$adapter->isUploaded() and !isset($data['updateId'])) { // 排除修改狀態
                $jsonModel->setVariable('success', false);
                $jsonModel->setVariable('message', array('upload_file[]'=>array('no_file','沒有上傳檔案')));
                return $jsonModel;
            }


            $em->persist($pageRes);
            $em->flush();

            // 檔案部份
            $tempId = sprintf("%02x",rand(1000,2000));
            $len = (int) strlen($tempId) / 2;

            $path = '';
            for($i=0;$i<$len-1; $i++)
                $path .= substr($tempId,$i*2,2).'/';

            $filePath  = $this->FileStorePath($path);
            $config = $this->getServiceLocator()->get('config');

            $adapter
            ->addValidator('Extension',false, $config['upload']['allow_type']);

            $i= 1;
            foreach ($adapter->getFileInfo() as $media) {

                $ext = $this->_findexts($media['name']);
                $ss = 'p'.$i.'-'.time();
                $fileName =  $ss. '.'.$ext;
                $adapter->addFilter('Rename', array(
                    'source' => $media['tmp_name'],
                    'target'=>$filePath . $fileName,
                    'overwrite'=>true
                ));

                if (!$adapter->receive($media['name']))
                {
                    $jsonModel->setVariable('success', false);
                    $jsonModel->setVariable('message', $adapter->getMessages());
                    return $jsonModel;
                }
                else {

                    $pageFile = new \Base\Entity\PageFile();
                    $pageFile->setPage($pageRes);
                    $pageFile->setFileExt($ext);
                    $pageFile->setFileName($path.$fileName);
                    $pageFile->setFileSize($media['size']);
                    $pageFile->setSourceName($media['name']);
                    $em->persist($pageFile);

                }
                $i++;
            }
            $em->flush();
            $jsonModel->setVariable('success', true);


        }
        else {
            $jsonModel->setVariable('success', false);
            $jsonModel->setVariable('message', $form->getMessages());
        }

        // tags
        if ($data['tags'])
            $this->setTags($data, $pageRes);

        return $jsonModel;

    }

    public function saveUrl($data, $pageRes)
    {
        $jsonModel = new JsonModel();
        $em = $this->getEntityManager();

        $form = new PageUrlForm();

        $form->bind($pageRes);

        $validateChain = new \Zend\Validator\ValidatorChain();
        $validateChain->attach(new \Zend\Validator\Uri());
        $filters = $form->getInputFilter();
        $filters->get('url')->setRequired(true)
            ->setValidatorChain($validateChain);
        $form->setInputFilter($filters);
        $form->setData($data);
        if ($form->isValid()) {

            $em->persist($pageRes);
            $em->flush();
            $jsonModel->setVariable('success', true);
        }
        else {
            $jsonModel->setVariable('success', false);
            $jsonModel->setVariable('message', $form->getMessages());
        }

        return $jsonModel;
    }

    public function deleteFileAction()
    {
        $id = (int)$this->params()->fromQuery('id');
        $em = $this->getEntityManager();
        /** @var  $res \base\Entity\PageFile */
        $res = $em->getRepository('Base\Entity\PageFile')->find($id);
        $jsonModel = new JsonModel();
        if ($res) {
            $fileName = $res->getFileName();
            $filePath = $this->FileStorePath().'/'.$fileName;
            if (is_file($filePath))
                unlink($filePath);

            $em->remove($res);
            $em->flush();
            $jsonModel->setVariable('success', true);
        }
        else
            $jsonModel->setVariable('success', false);

        return $jsonModel;
    }


    public function downloadAction()
    {
        $id = (int)$this->params()->fromQuery('id');
        $em = $this->getEntityManager();
        /** @var  $res \base\Entity\PageFile */
        $res = $em->getRepository('Base\Entity\PageFile')->find($id);
        if ($res) {
            $filePath  = $this->FileStorePath();
            return $this->Download($filePath.'/'.$res->getFileName(), $res->getSourceName());
        }
        else
            throw new \Exception('錯誤的檔案');
    }

    /**
     * 設定關鍵字
     * @param $data
     * @param $pageRes
     */
    public function setTags($data, $pageRes)
    {
        $em = $this->getEntityManager();
        foreach ($pageRes->getPageTags() as $tag){
            $em->remove($tag);
        }
        $em->flush();
        $filterChain = new \Zend\Filter\FilterChain();
        $filterChain->attach(new \Zend\Filter\StringTrim())
            ->attach(new \Zend\Filter\StripTags());
        foreach (explode(",", $data['tags']) as $val) {
            $val = $filterChain->filter($val);
            if (!$val) continue;

            $tagRes = $em->getRepository('Base\Entity\PageTag')->findOneBy(array('tag_name'=>$val));
            if (! $tagRes) {

                $tagRes = new \Base\Entity\PageTag();
                $tagRes->setTagName($val);
                $em->persist($tagRes);
                $em->flush();
            }
            $tagRes->addPage($pageRes);
            $em->persist($tagRes);

        }
        $em->flush();

    }

    protected function _findexts($filename)
    {

        $filename = strtolower($filename) ;

        $exts = explode(".", $filename) ;

        $n = count($exts)-1;

        $exts = $exts[$n];

        return $exts;

    }


}

