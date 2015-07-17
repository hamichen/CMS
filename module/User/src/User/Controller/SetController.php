<?php
namespace User\Controller;
use Base\Controller\BaseController;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

use User\Form\AccountFIlter;

use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use User\Form\RegisterForm as RegisterForm;
use Base\Model\User;
class SetController extends BaseController {

    protected $form;
    protected $storage;
    protected $authservice;


    public function getAuthService() {
        if (!$this->authservice) {
            $this->authservice = $this->getServiceLocator()->get('AuthService');
        }

        return $this->authservice;
    }

    public function getSessionStorage() {
        if (!$this->storage) {
            $this->storage = $this->getServiceLocator()
            ->get('Base\Model\MyAuthStorage');
        }

        return $this->storage;
    }

    public function indexAction() {        
        $viewModel = new ViewModel();
        $user = $this->getAuthService()->getIdentity();
        if ($user === NULL)            
          return  $this->redirect()->toRoute('home');
        
       
        $em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        
        $userRepository = $em->getRepository('Base\Entity\User')->find($user->getId());
        $viewModel->setVariable('user', $userRepository);
        return $viewModel;
    }

    public function userStateAction() {
        return new ViewModel();
    }

    public function registerAction() {
        $registerForm = new RegisterForm(
                $this->getRequest()->getBaseUrl() . '/captcha/');
        return array('form' => $registerForm);
    }

    public function generateAction() {
        $response = $this->getResponse();
        $response->getHeaders()->addHeaderLine('Content-Type', "image/png");

        $id = $this->params('id', false);

        if ($id) {

            $image = './data/captcha/' . $id;

            if (file_exists($image) !== false) {
                $imagegetcontent = @file_get_contents($image);

                $response->setStatusCode(200);
                $response->setContent($imagegetcontent);

                if (file_exists($image) == true) {
                    unlink($image);
                }
            }

        }

        return $response;
    }


    public function editAction()
    {
        $viewModel = new ViewModel();
        if (!$this->getAuthService()->getIdentity())
            return $this->redirect()->toRoute('home');

        $username = $this->getAuthService()->getIdentity()->getUserName();
        $registerForm = new RegisterForm();
        $registerForm->remove('captcha')
        ->remove('password')
        ->remove('re_password');

        $registerForm->get('submit')->setValue('修改資料');
        $registerForm->get('username')->setAttribute('readonly', true);
        $accountFilter = new AccountFIlter;
        $accountFilter->remove('password')
        ->remove('re_password');

        $registerForm->setInputFilter($accountFilter);


        $em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');

        $user = $em->getRepository('Base\Entity\User')->findOneBy(
                array('username'=>$username));


        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();
            	
            $registerForm->setData($data);
            if ($registerForm->isValid()) {
                $user->setFirstName($registerForm->get('first_name')->getValue());
                $user->setLastName($registerForm->get('last_name')->getValue());
                //echo $registerForm->get('birthday')->getValue(); exit;
                $birthday = new \DateTime($registerForm->get('birthday')->getValue());

                $user->setBirthday($birthday);
                $user->setSex($registerForm->get('sex')->getValue());
                $user->setEmail($registerForm->get('email')->getValue());
                $em->persist($user);
                $em->flush();
                $this->flashMessenger()->addMessage('修改完成');
                $this->redirect()->toRoute('user/default',array('controller'=>'set','action'=>'edit'));

            }
        }

        $registerForm->bind($user);
        $viewModel->setVariable('form', $registerForm);
        return $viewModel;
    }

    public function addressAction()
    {
        $viewModel = new ViewModel();
        return $viewModel;
    }

    public function editEmailAction()
    {
        $viewModel = new ViewModel();
        $user = $this->getAuthService()->getIdentity();
        $registerForm = new RegisterForm;
        $form = new \Zend\Form\Form('editForm');
        $form->add($registerForm->get('email')->setLabel('新的電子郵件'));
        $form->add($registerForm->get('submit')->setAttribute('value', '更改電子郵件'));
        $accountFilter = new AccountFIlter();
        $filter = new InputFilter();
        $filter->add($accountFilter->get('email'));
        $form->setInputFilter($filter);
        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
                $username = $user->getUserName();
                $user = $em->getRepository('Base\Entity\User')->findOneBy(
                        array('username'=>$username));
                if ($user){
                    $user->setEmail($form->get('email')->getValue());
                    $em->persist($user);
                    $em->flush();
                    $user->setPassword(null); // 清除密碼欄位
                    $this->getAuthService()->getStorage()
                    ->write($user);
                    $form->get('email')->setValue('');
                    $this->flashMessenger()->addSuccessMessage('Email 更改完成');
                    $this->getServiceLocator()->get('Zend\Log')->info($username.' edited email');
                    $this->redirect()->toRoute('user/default',array('controller'=>'set','action'=>'edit-email'));
                }
            }
            	
        }

        $viewModel->setVariable('user', $user);
        $viewModel->setVariable('form', $form);

        return $viewModel;
    }

    public function changePasswordAction()
    {
        $viewModel = new ViewModel();
        $username = $this->getAuthService()->getIdentity()->getUsername();
        $registerForm = new RegisterForm();
        $form = new \Zend\Form\Form('changePasswordForm');
        $form->add($registerForm->get('password'));
        $form->add($registerForm->get('re_password'));
      //  $form->add($registerForm->get('submit')->setAttribute('value', '修改密碼'));
        $accountFilter = new AccountFIlter();
        $filter = new InputFilter();
        $filter->add($accountFilter->get('password'))
        ->add($accountFilter->get('re_password'));
        $form->setInputFilter($filter);

        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
                $user = $em->getRepository('Base\Entity\User')
                ->findOneBy(array('username'=>$username));
                if ($user) {
                    //$user->setPassword(md5($form->get('password')->getValue()));
                    $user->setPassword(\Zend\Ldap\Attribute::createPassword($form->get('password')->getValue()));

                    $em->persist($user);
                    $em->flush();

                    $this->getServiceLocator()->get('Zend\Log')->info($username.' changed password');
                    $this->flashMessenger()->addSuccessMessage('更改密碼成功!');
                    return $this->redirect()->refresh();

                }
            }
        }

        $viewModel->setVariable('form', $form);
        return $viewModel;
    }

    public function uploadAvatarAction()
    {
        $viewModel = new ViewModel();

        return $viewModel;
    }

    public function cropAction()
    {
        $jsonModel = new JsonModel();
        $gd = new \User\Model\GdImage();

        foreach($_POST['imgcrop'] as $k => $v) {

            $targetPath = './data/tmp/';
            $targetFile =  str_replace('//','/',$targetPath) . $v['filename'];

            $gd->crop($targetFile, $v['x'], $v['y'], $v['w'], $v['h']);

            $fileName = $targetPath.$v['filename'];

            $type = end(explode('.',$fileName));
            $dm = $this->getDocumentManager();
            $id =  $user = $this->getAuthService()->getIdentity()->getUsername();
            $kindId = 'user-avatar-' . $id;

            if ($image = $dm->getRepository('Base\Document\Image')->findOneBy(array('kindId' => $kindId))) {
                $image->setFile($fileName);
                $dm->persist($image);
                $dm->flush();
                unlink($fileName);
            }
        }
        return $jsonModel;
    }

    public function uploadImageAction()
    {
        $allowedExtensions = array('jpeg','jpg','gif','png');
        $sizeLimit = 2 * 1024 * 1024; // max file size in bytes
        $uploader = new \User\Model\FileUploader($allowedExtensions, $sizeLimit);
        $targetPath = './data/tmp/';
        if (!is_dir($targetPath))
            mkdir($targetPath);
        $result = $uploader->handleUpload($targetPath, false, '');

        $dm = $this->getDocumentManager();
        $id =  $user = $this->getAuthService()->getIdentity()->getUsername();
        $kindId = 'user-avatar-' . $id;
        // 先找到舊檔案再刪除
        if ($find = $dm->getRepository('Base\Document\Image')->findOneBy(array('kindId' => $kindId))) {
            $dm->remove($find);
            $dm->flush();
        }
        $fileName = $targetPath.$result['filename'];

        $sourceImg = new \Imagick($fileName);
        $sourceImg->resizeImage(300, 300, \imagick::FILTER_LANCZOS, 1, TRUE);
        $sourceImg->writeImage();

        $type = end(explode('.',$fileName));

        $image = new  \Base\Document\Image();
        $image->setName($result['filename']);
        $image->setFile($fileName);
        $image->setType($type);
        $image->setKindId($kindId);
        $dm->persist($image);
        $dm->flush();

        $result['showName'] = '/user/sign/show-image?id='.$user;


// to pass data through iframe you will need to encode all html tags
        echo json_encode($result);exit();
    }

    /**
     * 新增個人化選單
     * @return JsonModel
     * @throws \Doctrine\ORM\ORMException
     */
    public function addAnchorResourceAction()
    {
        $jsonModel = new JsonModel();
        $user = $this->getAuthService()->getIdentity();
        $uri = $this->params()->fromPost('uri');

        $tempArr = explode('/', $uri);

        $em = $this->getEntityManager();
        $resourceRes = $em->getRepository('Base\Entity\Resource')->findOneBy(
            array('resource_name'=>$tempArr[3]));
        $resourceUserRes = new \Base\Entity\ResourceUser();
        $resourceUserRes->setUser($em->getReference('Base\Entity\User', $user->getId()));
        $resourceUserRes->setResource($em->getReference('Base\Entity\Resource', $resourceRes->getId()));
        $resourceUserRes->setCreateTime(new \DateTime());
        $resourceUserRes->setUri($uri);
        $em->persist($resourceUserRes);
        $em->flush();
        $jsonModel->setVariable('success', true);
        $session = $this->getServiceLocator()->get('SchoolSession');
        $session->user_anchor = [];
        $this->flashMessenger()->addInfoMessage('本頁已加到我的選單');

        return $jsonModel;

    }

    /**
     * 移除個人化選單
     * @return JsonModel
     * @throws \Doctrine\ORM\ORMException
     */
    public function removeAnchorResourceAction()
    {
        $jsonModel = new JsonModel();
        $user = $this->getAuthService()->getIdentity();
        $uri = $this->params()->fromPost('uri');

        $em = $this->getEntityManager();
       if ($res = $em->createQuery('SELECT u FROM Base\Entity\ResourceUser u
       LEFT JOIN u.user user
       WHERE u.uri =:uri AND user.id=:user_id')
           ->setParameter('uri', $uri)
           ->setParameter('user_id',$user->getId() )
           ->getResult()) {
           $em->remove($res[0]);
           $em->flush();
           $jsonModel->setVariable('success', true);
           $session = $this->getServiceLocator()->get('SchoolSession');
           $session->user_anchor = [];
           $this->flashMessenger()->addInfoMessage('本頁已從我的選單中移除');
       }
        else
            $jsonModel->setVariable('success', false);

        return $jsonModel;

    }

    public function simulationAction()
    {
        $em = $this->getEntityManager();
        // 檢查是否有模擬的權限
        $this->checkSimulationPermission();

        $semester = $em->getRepository('Base\Entity\Semester')->current();
        // 僅顯示在職
        $teachers = $em->getRepository('Base\Entity\SemesterTeacher')->getSemesterTeacher($semester->getId(),0);
        $form = new Form('simulationForm');
        $form->setAttribute('action','/user/set/set-simulation');
        $form->add(array(
            'name' => 'username',
            'type' => 'Zend\Form\Element\Select',
            'options' => array(
                'label' => '選擇人員',
                'value_options' => $teachers
            ),
            'attributes' => array(
                'size' => 10
            )
        ));
        $viewModel = new ViewModel();
        $viewModel->setVariable('form', $form);
        return $viewModel;
    }

    /**
     * 角色模擬
     * @return JsonModel
     */
    public function setSimulationAction(){
        // 檢查是否有模擬的權限
        $simulationId = $this->checkSimulationPermission();

        $jsonModel = new JsonModel();
        if ($this->getRequest()->isPost()) {
           $assignUser = $this->getAuthService()->getIdentity();

            $em = $this->getEntityManager();
            $users = $em->getRepository('Base\Entity\User')->find($this->params()->fromPost('username'));

            $users->setPassword(null); // 清除密碼欄位
            $this->getAuthService()
                ->getStorage()
                ->write($users);

            $session= $this->getServiceLocator()->get('SchoolSession');
            $semesterTeacher = $session->semesterTeacher;

            $session->simulationUser = $assignUser;
            $session->simulationTeacher = $semesterTeacher;

            $session->group = [];
            $session->user_anchor = [];

            $simulationLog = new \Base\Entity\UserSimulationLog();
            $simulationLog->setStartTime(new \DateTime());
            $simulationLog->setUserSimulation( $em->getReference('Base\Entity\UserSimulation',$simulationId));
            $httpIp = $this->getRequest()->getServer('REMOTE_ADDR');
            $simulationLog->setFromIp($httpIp);
            $em->persist($simulationLog);
            $em->flush();
            $session->simulation_log_id = $simulationLog->getId();


            $jsonModel->setVariable('success',true);

        }
        else
            $jsonModel->setVariable('success',false);

        return $jsonModel;


    }

    public function revokeSimulationAction()
    {
        $jsonModel = new JsonModel();
        $session = $this->getServiceLocator()->get('SchoolSession');
        $this->getAuthService()
            ->getStorage()
            ->write($session->simulationUser);
        $session->group = [];
        $session->user_anchor = [];
        $session->simulationTeacher = [];
        $session->simulationUser = [];
        $em = $this->getEntityManager();
        // 記錄回復時間
        if ($simulationLog = $em->getRepository('Base\Entity\UserSimulationLog')
                ->find($session->simulation_log_id)) {
            $simulationLog->setEndTime(new \DateTime());
            $em->persist($simulationLog);
            $em->flush();
        }


        $jsonModel->setVariable('success',true);
        return $jsonModel;

    }

    public function checkSimulationPermission()
    {
        $em = $this->getEntityManager();
        $user = $this->getAuthService()->getIdentity();

        // 檢查是否有模擬權限
        $qb = $em->createQueryBuilder()
            ->select('u')
            ->from('Base\Entity\UserSimulation','u')
            ->leftJoin('u.userRelatedByUserId','user')
            ->where('user.username=:username')
            ->andWhere('u.is_active=1')
            ->setParameter('username',$user->getUsername())
            ->getQuery()
            ->getArrayResult();
        if (count($qb) == 0 )
            throw new \Exception("錯誤的身分");


        return $qb[0]['id'];
    }
}
