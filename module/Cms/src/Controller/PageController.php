<?php

namespace Cms\Controller;

use Base\Controller\BaseController;
use Base\Document\CmsFile;
use Base\Entity\Page;
use Base\Entity\PageFile;
use Cms\Form\PageFileForm;
use Cms\Form\PageLayoutForm;
use Cms\Form\PageTextForm;
use Cms\Form\PageUrlForm;
use Cms\Form\SelectPageForm;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Zend\Paginator\Paginator;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

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
        $menuOptions = $menuRes->getMenuOptions();
        // 取消首頁顯示
        unset($menuOptions[1]);

        $pageForm->get('menu_id')->setValueOptions($menuOptions);
        if ($id = (int)$this->params()->fromQuery('menu_id')) {
            $pageForm->get('menu_id')->setValue($id);
        }

//        $pageKindArray = $em->getRepository('Base\Entity\Page')->getPageKindArray();

        $viewModel->setVariable('form', $pageForm);

        return $viewModel;
    }


    public function getPageListAction()
    {

        $viewModel = new ViewModel();
        $em = $this->getEntityManager();
        $id = $this->params()->fromQuery('id');

        /** @var  $menuRes \Base\Entity\MenuRepository */
        $menuRes = $em->getRepository('Base\Entity\Menu');
        $menuData = $menuRes->find($id);
        $params = unserialize($menuData->getParams());

        $menuLayout = $em->getRepository('Base\Entity\Menu')->getPageLayout($menuData->getLayout());
        $pageKindArray = $menuLayout['tools'];
        $viewModel->setVariable('pageKindArray', $pageKindArray);

        $qb = $em->createQueryBuilder()
            ->select('u, f')
            ->from('Base\Entity\page', 'u')
            ->leftJoin('u.menu', 'm')
            ->leftJoin('u.pageFiles', 'f')
            ->where('m.id=:id')
            ->setParameter('id', $id);

        switch ($params['order_kind']) {
            case 'desc' :
                $qb->orderBy('u.update_time', 'desc');
                break;
            case 'asc' :
                $qb->orderBy('u.update_time', 'asc');
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

        if ($page = $this->params()->fromQuery('page'))
            $paginator->setCurrentPageNumber($page);

      //  $pageKindArray = $em->getRepository('base\Entity\Page')->getPageKindArray();

  //      $viewModel->setVariable('pagekindArray', $pageKindArray);

        $viewModel->setVariable('paginator', $paginator);

        $viewModel->setVariable('params', $params);

        $viewModel->setVariable('id', $id);

        return $viewModel;

    }


    public function getPageAction()
    {
        $viewModel = new ViewModel();
        $em = $this->getEntityManager();
        $kind = $this->params()->fromQuery('kind');
        $orderId = null;
        $menuId = $this->params()->fromQuery('menu_id');
        /** @var  $menuRes \Base\Entity\Menu */
        $menuRes = $em->getRepository('Base\Entity\Menu')->find($menuId);

        if ($id = (int)$this->params()->fromQuery('id')) {
            $pageRes = $em->getRepository('Base\Entity\Page')->find($id);
            $kind = $pageRes->getKind();
        } else {
            $pageRes = new \Base\Entity\Page();
            // 最後一篇文章
            $qb = $em->createQueryBuilder()
                ->select('count(u.id) as cc')
                ->from('Base\Entity\Page', 'u')
                ->leftJoin('u.menu', 'm')
                ->where('m.id=:id')
                ->setParameter('id', $menuId)
                ->getQuery()
                ->getArrayResult();
            $orderId = $qb[0]['cc'] + 1;
        }

        $params = unserialize($menuRes->getParams());

        switch ($kind) {
            case 'layout':
                $form = new PageLayoutForm();
                $form->bind($pageRes);
                if ($orderId)
                    $form->get('order_id')->setValue($orderId);
                if (isset($params['title_limit']) and $params['title_limit'])
                    $form->get('title')->setAttribute('readonly', 'true');


                $viewModel->setTemplate('cms/page/page-layout.twig');
                break;
            case 'text':
                $form = new PageTextForm();

                $form->bind($pageRes);
                if ($orderId)
                    $form->get('order_id')->setValue($orderId);
                $viewModel->setVariable('files', $pageRes->getPageFiles());
                // 分類
                if ($params['term'] and count($termArr = explode(",",$params['term'])) > 0) {
                    $termFilteredArr = [];
                    foreach ($termArr as $val){
                        $filterVal = trim($val);
                        $termFilteredArr[$filterVal] = $filterVal;
                    }
                    $form->get('term')->setValueOptions($termFilteredArr);
                }
                else
                    $form->remove('term');

                $viewModel->setTemplate('cms/page/page-text.twig');

                break;
            case 'file':
                $form = new PageFileForm();

                $form->bind($pageRes);
                if ($orderId)
                    $form->get('order_id')->setValue($orderId);

                // 分類
                if ($params['term'] and count($termArr = explode(",",$params['term'])) > 0) {
                    $termFilteredArr = [];
                    foreach ($termArr as $val){
                        $filterVal = trim($val);
                        $termFilteredArr[$filterVal] = $filterVal;
                    }
                    $form->get('term')->setValueOptions($termFilteredArr);
                }
                else
                    $form->remove('term');

                $viewModel->setVariable('files', $pageRes->getPageFiles());
                $viewModel->setVariable('files', $pageRes->getPageFiles());
                $viewModel->setTemplate('cms/page/page-file.twig');

                break;
            case 'url' :
                $form = new PageUrlForm();
                $menuOptions = $em->getRepository('Base\Entity\Menu')->getMenuOptions();

                $form->get('link_menu')->setValueOPtions($menuOptions);


                // 分類
                if ($params['term'] and count($termArr = explode(",",$params['term'])) > 0) {
                    $termFilteredArr = [];
                    foreach ($termArr as $val){
                        $filterVal = trim($val);
                        $termFilteredArr[$filterVal] = $filterVal;
                    }
                    $form->get('term')->setValueOptions($termFilteredArr);
                }
                else
                    $form->remove('term');


                $form->bind($pageRes);
                if ($orderId)
                    $form->get('order_id')->setValue($orderId);

                $viewModel->setVariable('files', $pageRes->getPageFiles());
                $viewModel->setTemplate('cms/page/page-url.twig');

                break;
        }
        $viewModel->setVariable('form', $form);
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

            $dm = $this->getDocumentManager();
            //  檔案刪除
            if ($res->getKind() == 'file' || $res->getKind() == 'text') {
                /** @var  $file \Base\Entity\PageFile */
                foreach ($res->getPageFiles() as $file) {
                    $dm->createQueryBuilder('Base\Document\CmsFile')
                        ->findAndRemove()
                        ->field('id')->equals($file->getFileName())
                        ->getQuery()
                        ->execute();

                    $em->remove($file);
                }

            }
            $em->remove($res);
            $em->flush();
            $jsonModel->setVariable('success', true);
        }

        return $jsonModel;
    }

    /**
     * 儲存
     * @return JsonModel
     */
    public function saveAction()
    {

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $em = $this->getEntityManager();

            if ($id = $data['id']) {
                /** @var  $pageRes \Base\Entity\Page */
                $pageRes = $em->getRepository('Base\Entity\Page')->find($id);
                $pageRes->setUpdateTime(new \DateTime());

            } else {
                $pageRes = new Page();
                $pageRes->setCreateTime(new \DateTime());

            }
            /** @var  $user \Base\Entity\User */
            $user = $this->getAuthService()->getIdentity();

            $pageRes->setMenu($em->getReference('Base\Entity\Menu', $data['menu_id']));
            $pageRes->setUser($em->getReference('Base\Entity\user', $user->getUsername()));
            $pageRes->setKind($data['kind']);


            switch ($data['kind']) {
                case 'layout':
                    return $this->saveLayout($data, $pageRes);
                    break;

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
        $form->getInputFilter()->get('title')->setRequired(true);

        $form->setData($data);
        if ($form->isValid()) {

            $em->persist($pageRes);
            $em->flush();
            $config = $this->getServiceManager()->get('config');

            $adapter = new \Zend\File\Transfer\Adapter\Http();

            $adapter
                ->addValidator('Extension', false, $config['file_type']['cms']['file']);

            $media = $adapter->getFileInfo();

            if ($media) {
                // 檔案部份

                $dm = $this->getDocumentManager();

                $sourceImg = new \Imagick($media['upload_file']['tmp_name']);
                $sourceImg->resizeImage(306, 306, \imagick::FILTER_LANCZOS, 1, TRUE);
                $sourceImg->writeImage();

                // 存在 mongodb
                $cmsFile = new CmsFile();
                $cmsFile->setName($media['upload_file']['name']);
                $cmsFile->setFile($media['upload_file']['tmp_name']);
                $cmsFile->setType($media['upload_file']['type']);
                $cmsFile->setCmsId($pageRes->getId());
                $dm->persist($cmsFile);
                $dm->flush();

                $ext = $this->_findexts($media['upload_file']['name']);
                $pageFile = new PageFile();
                $pageFile->setPage($pageRes);
                $pageFile->setFileExt($ext);
                $pageFile->setFileName($cmsFile->getId());
                $pageFile->setFileSize($media['upload_file']['size']);
                $pageFile->setSourceName($media['upload_file']['name']);
                $em->persist($pageFile);
                $em->flush();
            }




            // tags
            if ($this->params()->fromPost('tags'))
                $this->setTags($data, $pageRes);


            $jsonModel->setVariable('success', true);
        } else {
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
        $form->getInputFilter()->get('title')->setRequired(true);
        $form->setData($data);
        if ($form->isValid()) {
            $files = $this->params()->fromFiles();

            if (!isset($files['upload_file']) and !isset($data['id'])) { // 排除修改狀態
                $jsonModel->setVariable('success', false);
                $jsonModel->setVariable('message', array('upload_file[]' => array('no_file', '沒有上傳檔案')));
                return $jsonModel;
            }


            $em->persist($pageRes);
            $em->flush();

            // 檔案部份
            if (isset($files['upload_file'])) {
                $config = $this->getServiceManager()->get('config');

                $fileType = $config['file_type']['cms']['file'];
                $dm = $this->getDocumentManager();


                foreach ($files['upload_file'] as $media) {
                    $extStr = str_replace(",", "|", $fileType);
                    //檢查檔案格式
                    if (preg_match('/\.(' . $extStr . ')$/i', $media['name'])) {
                        // 存在 mongodb
                        $cmsFile = new CmsFile();
                        $cmsFile->setName($media['name']);
                        $cmsFile->setFile($media['tmp_name']);
                        $cmsFile->setType($media['type']);
                        $cmsFile->setCmsId($pageRes->getId());
                        $dm->persist($cmsFile);
                        $dm->flush();

                        $ext = $this->_findexts($media['name']);
                        $pageFile = new PageFile();
                        $pageFile->setPage($pageRes);
                        $pageFile->setFileExt($ext);
                        $pageFile->setFileName($cmsFile->getId());
                        $pageFile->setFileSize($media['size']);
                        $pageFile->setSourceName($media['name']);
                        $em->persist($pageFile);
                        $em->flush();
                    }
                }
            }

            $jsonModel->setVariable('success', true);


        } else {
            $jsonModel->setVariable('success', false);
            $jsonModel->setVariable('message', $form->getMessages());
        }

        // tags
        if (isset($data['tags']) and $data['tags'])
            $this->setTags($data, $pageRes);

        return $jsonModel;

    }

    /**
     * 儲存連結
     * @param $data
     * @param $pageRes
     * @return JsonModel
     */
    public function saveUrl($data, $pageRes)
    {
        $jsonModel = new JsonModel();
        $em = $this->getEntityManager();

        $form = new PageUrlForm();

        $menuOptions = $em->getRepository('Base\Entity\Menu')->getMenuOptions();
        $form->get('link_menu')->setValueOPtions($menuOptions);

        /** @var  $menuRes \Base\Entity\Menu */
        $menuRes = $em->getRepository('Base\Entity\Menu')
            ->find($this->params()->fromPost('menu_id'));

        $params = unserialize($menuRes->getParams());
        // 分類
        if ($params['term'] and count($termArr = explode(",",$params['term'])) > 0) {
            $termFilteredArr = [];
            foreach ($termArr as $val){
                $filterVal = trim($val);
                $termFilteredArr[$filterVal] = $filterVal;
            }
            $form->get('term')->setValueOptions($termFilteredArr);
        }
        else
            $form->remove('term');

        $form->bind($pageRes);


        if (!$data['link_menu']) {
            $validateChain = new \Zend\Validator\ValidatorChain();
            $validateChain->attach(new \Zend\Validator\Uri());
            $filters = $form->getInputFilter();

            $filters->get('url')->setRequired(true)
                ->setValidatorChain($validateChain);
            $form->setInputFilter($filters);
        }

        $form->setData($data);
        if ($form->isValid()) {

            $em->persist($pageRes);
            $em->flush();
            $config = $this->getServiceManager()->get('config');

            $adapter = new \Zend\File\Transfer\Adapter\Http();

            $adapter
                ->addValidator('Extension', false, $config['upload']['allow_type']);

            $media = $adapter->getFileInfo();

            if ($media) {
                // 檔案部份
                $tempId = sprintf("%02x", rand(1000, 2000));
                $len = (int)strlen($tempId) / 2;

                $path = '';
                for ($i = 0; $i < $len - 1; $i++)
                    $path .= substr($tempId, $i * 2, 2) . '/';

                $filePath = $this->FileStorePath('cms/' . $path);

                $ext = $this->_findexts($media['upload_file']['name']);
                $ss = 'p' . $i . '-' . time();
                $fileName = $ss . '.' . $ext;

                $sourceImg = new \Imagick($media['upload_file']['tmp_name']);
                $sourceImg->resizeImage(306, 306, \imagick::FILTER_LANCZOS, 1, TRUE);
                $sourceImg->writeImage();

                $adapter->addFilter('Rename', array(
                    'source' => $media['upload_file']['tmp_name'],
                    'target' => $filePath . $fileName,
                    'overwrite' => true
                ));

                if ($adapter->receive($media['upload_file']['name'])) {
                    $pageFile = new \Base\Entity\PageFile();
                    $pageFile->setPage($pageRes);
                    $pageFile->setFileExt($ext);
                    $pageFile->setFileName($path . $fileName);
                    $pageFile->setFileSize($media['upload_file']['size']);
                    $pageFile->setSourceName($media['upload_file']['name']);
                    $em->persist($pageFile);
                    $em->flush();
                }
            }
            $jsonModel->setVariable('success', true);
        } else {
            $jsonModel->setVariable('success', false);
            $jsonModel->setVariable('message', $form->getMessages());
        }

        return $jsonModel;
    }

    /**
     * 儲存頁面
     * @param $data
     * @param $pageRes \Base\Entity\Page
     * @return JsonModel
     */
    public function saveLayout($data, $pageRes)
    {

        $jsonModel = new JsonModel();
        $em = $this->getEntityManager();

        $form = new PageLayoutForm();
        $pageRes->setIsPublished(1);

        $form->bind($pageRes);
        $form->setData($data);
        if ($form->isValid()) {
            $em->persist($pageRes);
            $em->flush();
            $jsonModel->setVariable('success', true);
        } else {
            $jsonModel->setVariable('success', false);
            $jsonModel->setVariable('message', $form->getMessages());
        }

        return $jsonModel;
    }


    public function deleteFileAction()
    {
        $id = (int)$this->params()->fromQuery('id');
        $em = $this->getEntityManager();
        $dm = $this->getDocumentManager();
        /** @var  $res \base\Entity\PageFile */
        $res = $em->getRepository('Base\Entity\PageFile')->find($id);
        $jsonModel = new JsonModel();
        if ($res) {
            $fileName = $res->getFileName();
            $file = $dm->getRepository('Base\Document\CmsFile')->find($fileName);
            if ($file){
                $dm->remove($file);
                $dm->flush();
            }
            $em->remove($res);
            $em->flush();
            $jsonModel->setVariable('success', true);
        } else
            $jsonModel->setVariable('success', false);

        return $jsonModel;
    }


    public function downloadAction()
    {
        $id = $this->params()->fromQuery('id');
        /** @var \Zend\Http\Response $tmpResponse */
        $tmpResponse = $this->getResponse();
        $dm = $this->getDocumentManager();
        $data = $dm->getRepository('Base\Document\CmsFile')->find($id);
        if ($data) {
            $tmpResponse->getHeaders()->addHeaderLine('Content-Type', $data->getType())
                ->addHeaderLine('Content-Disposition', 'attachment; filename="' . $data->getName() . '"')
                ->addHeaderLine('Content-Length', $data->getLength());
            $tmpResponse->setContent($data->getFile()->getBytes());
            return $tmpResponse;
        } else {
            return $tmpResponse->setStatusCode(404);
        }
    }

    /**
     * 設定關鍵字
     * @param $data
     * @param $pageRes
     */
    public function setTags($data, $pageRes)
    {
        $em = $this->getEntityManager();
        foreach ($pageRes->getPageTags() as $tag) {
            $em->remove($tag);
        }
        $em->flush();
        $filterChain = new \Zend\Filter\FilterChain();
        $filterChain->attach(new \Zend\Filter\StringTrim())
            ->attach(new \Zend\Filter\StripTags());
        foreach (explode(",", $data['tags']) as $val) {
            $val = $filterChain->filter($val);
            if (!$val) continue;

            $tagRes = $em->getRepository('Base\Entity\PageTag')->findOneBy(array('tag_name' => $val));
            if (!$tagRes) {

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

        $filename = strtolower($filename);

        $exts = explode(".", $filename);

        $n = count($exts) - 1;

        $exts = $exts[$n];

        return $exts;

    }


}

