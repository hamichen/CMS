<?php
namespace User\Controller;

use Base\Controller\BaseController;
use Zend\InputFilter\InputFilter;
use Zend\Captcha\Image as CaptchaImage;
use Zend\Mail;
use User\Form\AccountFIlter;
use User\Form\LostPasswordForm;
use Zend\Form\Annotation\AnnotationBuilder;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use User\Model\User;
use User\Form\LoginForm;
use User\Form\LoginFilter;

class SignController extends BaseController
{

    protected $authservice;

    protected $form;

    protected $storage;

    protected $em;


    public function getRepository()
    {
        if (null === $this->em)
            $this->em = $this->getEntityManager()->getRepository('Base\Entity\User');
        return $this->em;
    }

    public function getSessionStorage()
    {
        if (!$this->storage) {
            $this->storage = $this->getServiceLocator()->get('Base\Model\AuthStorage');
            // $this->storage = $this->getServiceLocator()->get('SchoolSession');
        }

        return $this->storage;
    }

    public function loginAction()
    {
        $viewModel = new ViewModel();
        $errorMessage = '';
        $ap = $this->getEvent()
            ->getRouteMatch()
            ->getParam('ap');

        // $form = $this->getForm();
        $loginFilter = new LoginFilter();

        $form = $this->_getForm();

        $form->setInputFilter($loginFilter);
        if ($ap) {
            $form->add(array(
                'name' => 'ap',
                'attributes' => array(
                    'type' => 'hidden',
                    'value' => $ap
                )
            ));
            $errorMessage = '您沒有權限進入頁面';
        }
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                // check authentication...
                $username = $form->get('username')->getValue();
                $password = $form->get('password')->getValue();

                $auth = $this->getServiceLocator()->get('doctrine.authenticationservice.orm_default');
                // echo get_class($auth); exit;
                $auth->getAdapter()->setIdentityValue($username);
                $auth->getAdapter()->setCredentialValue($password);

                $result = $auth->authenticate();

                if ($result->isValid()) {
                    if ($ap = $request->getPost()->get('ap'))
                        $redirect = str_replace(':', '/', $ap);
                    else
                        $redirect = '';
                    // check if it has rememberMe :
                    /*                    if ($request->getPost('rememberme') == 1) {
                                            $this->getSessionStorage()->setRememberMe(1);
                                            // set storage again
                                            $this->getAuthService()->setStorage($this->getSessionStorage());
                                        }*/
                    $em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
                    $users = $em->getRepository('Base\Entity\User')->findOneBy(array(
                        'username' => $form->get('username')
                                ->getValue()
                    ));


                    $users->setLastLogintime(new \DateTime());
                    $httpIp = $this->getRequest()->getServer('REMOTE_ADDR');
                    $users->setLastLoginip($httpIp);
                    $em->persist($users);
                    $em->flush();

                    $users->setPassword(null); // 清除密碼欄位
                    $this->getAuthService()
                        ->getStorage()
                        ->write($users);

                    $this->getServiceLocator()
                        ->get('Zend\Log')
                        ->info($request->getPost('username') . ' login from ' . $httpIp);


                    $base = $this->request->getBasePath();
                    $response = $this->getResponse();
                    $response->setStatusCode(302);
                    $response->getHeaders()->addHeaderLine('Location', $base . '/' . $redirect);
                    return $response;
                }
                else {
                    $errorMessage = '帳號密碼有誤，請檢查!';
                }
            }
        }

        $viewModel->setVariable('errorMessage', $errorMessage);
        $viewModel->setVariable('form', $form);
        return $viewModel;
    }

    protected function _getForm()
    {
        $form = new LoginForm();

       /* if ($moduleParam['useCaptcha']) {
            $dirData = './data';
            $urlCaptcha = '/captcha/' ;
            $captchaImage = new CaptchaImage(  array(
                    'font' => './fonts/arial.ttf',
                    'width' => 200,
                    'height' => 80,
                    'wordlen' => $moduleParam['wordlen'],
                    'dotNoiseLevel' => 40,
                    'lineNoiseLevel' => 3)
            );
            $captchaImage->setImgDir($dirData.'/captcha');
            $captchaImage->setImgUrl($urlCaptcha);

            $form->add(array(
                'type' => 'Zend\Form\Element\Captcha',
                'name' => 'captcha',
                'options' => array(
                    'label' => '輸入圖形驗證碼*',
                    'captcha' => $captchaImage,
                ),
                'attributes' => array(
                    'id' => 'captcha'
                )
            ));

        }*/
        return $form;
    }

    public function getForm()
    {
        if (!$this->form) {
            $user = new User();

            $builder = new AnnotationBuilder();

            $this->form = $builder->createForm($user);
        }

        return $this->form;
    }

    public function getAuthService()
    {
        if (!$this->authservice) {
            $this->authservice = $this->getServiceLocator()->get('doctrine.authenticationservice.orm_default');
        }

        return $this->authservice;
    }

    public function generateAction()
    {
        $response = $this->getResponse();
        $response->getHeaders()->addHeaderLine('Content-Type', "image/png");

        $id = $this->params('id', false);

        if ($id) {

            $image = './data/captcha/' . $id;

            if (file_exists($image) !== false) {
                $imageGetContent = @file_get_contents($image);

                $response->setStatusCode(200);
                $response->setContent($imageGetContent);

                if (file_exists($image) == true) {
                    unlink($image);
                }
            }

        }

        return $response;
    }

    public function refreshAction()
    {
       $form = $this->_getForm();


        $captcha = $form->get('captcha')->getCaptcha();

        $data = array();

        $data['id']  = $captcha->generate();
        $data['src'] = $captcha->getImgUrl() .
            $captcha->getId() .
            $captcha->getSuffix();

        $jsonModel = new JsonModel();
        $jsonModel->setVariables($data);
        return $jsonModel;
    }

    /**
     * 登出
     *
     * @return \Zend\Http\Response
     */
    public function logoutAction()
    {
        if (!$this->getAuthService()->getIdentity())
            return $this->redirect()->toRoute('home');
        $username = $this->getAuthService()
            ->getIdentity()
            ->getUsername();
        //   $this->getSessionStorage()->forgetMe();
        $this->getAuthService()->clearIdentity();
        $httpIp = $this->getRequest()->getServer('REMOTE_ADDR');
        $this->getServiceLocator()
            ->get('Zend\Log')
            ->info($username . ' logout from ' . $httpIp);
        // $this->flashmessenger()->addMessage("您已經登出");
         $session = new \Zend\Session\Container('user');
         $session->getManager()->destroy();
     /*   $session = $this->getServiceLocator()->get('SchoolSession');
        $session->getManager()->destroy();*/
        return $this->redirect()->toRoute('home');
    }

    public function lostPasswordAction()
    {
        $viewModel = new ViewModel();
        $form = new LostPasswordForm();
        $accountFilter = new AccountFIlter();
        $filter = new InputFilter();
        $filter->add($accountFilter->get('email'));

        $form->setInputFilter($filter);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
                // 找到使用者
                if ($user = $em->getRepository('Base\Entity\User')->findOneBy(array(
                    // 'username' => $form->get('username')->getValue(),
                    'email' => $form->get('email')
                            ->getValue()
                ))
                ) {
                    $md5 = md5(time() . $user->getUserName() . $user->getEmail());
                    $user->setCheckCode($md5);
                    $em->persist($user);
                    $em->flush();

                    $view = new \Zend\View\Renderer\PhpRenderer();
                    $resolver = new \Zend\View\Resolver\TemplateMapResolver();
                    $resolver->setMap(array(
                        'mailLayout' => __DIR__ . '/../../../../Application/view/layout/layout-mail.phtml',
                        'mailTemplate' => __DIR__ . '/../../../view/user/sign/lost-password-mail.phtml'
                    ));
                    $view->setResolver($resolver);
                    $uri = $this->getRequest()->getUri();
                    $scheme = $uri->getScheme();
                    $host = $uri->getHost();
                    $base = sprintf('%s://%s%s', $scheme, $host, $this->getRequest()->getBasePath());

                    $viewModel->setTemplate('mailTemplate')->setVariables(array(
                        'user' => $user,
                        'url' => $base
                    ));

                    $content = $view->render($viewModel);

                    $viewLayout = new ViewModel();
                    $viewLayout->setTemplate('mailLayout')->setVariables(array(
                        'content' => $content
                    ));
                    $body = $view->render($viewLayout);

                    $bodyPart = new \Zend\Mime\Message();

                    $bodyMessage = new \Zend\Mime\Part($body);
                    $bodyMessage->type = 'text/html';

                    $bodyPart->setParts(array(
                        $bodyMessage
                    ));

                    $message = new Mail\Message();

                    $message->addTo($user->getEmail(), $user->getusername());
                    $message->setFrom('service@sfs.tw', '系統通知信');
                    $message->setSubject('重設密碼通知信');
                    $message->setBody($bodyPart);
                    $message->setEncoding('UTF-8');

                    $transport = new Mail\Transport\Sendmail();
                    $transport->send($message);

                    // $viewModel->setTemplate('user/login/send-mail.phtml');
                    $this->flashmessenger()->addMessage($user->getEmail());
                    return $this->redirect()->toRoute('user/default', array(
                        'controller' => 'sign',
                        'action' => 'send-mail'
                    ));
                    // return $viewModel;
                } else
                    $this->flashmessenger()->addMessage('您輸入了錯誤的帳號或電子郵件');
            }
        }
        $viewModel->setVariable('form', $form);
        $viewModel->setVariable('flashMessages', $this->flashMessenger()
            ->getMessages());

        return $viewModel;
    }

    /**
     * 更改密碼
     */
  /*  public function resetPasswordAction()
    {
        $em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        $viewModel = new ViewModel();
        $code = $this->getEvent()
            ->getRouteMatch()
            ->getParam('code');
        // 查詢 code 是否正確
        if (!$user = $em->getRepository('Base\Entity\User')->findOneBy(array(
            'checkCode' => $code
        ))
        ) {
            $this->flashMessenger()->addErrorMessage('錯誤的驗證碼');
            return $this->redirect()->toRoute('user/default', array(
                'controller' => 'sign',
                'action' => 'error'
            ));
        }

        $form = new \Zend\Form\Form();

        $regForm = new RegisterForm();
        $form->add($regForm->get('password'));
        $form->add($regForm->get('re_password'));
        $form->add($regForm->get('submit')
            ->setAttribute('value', '更改密碼'));

        $accountFilter = new AccountFIlter();
        $fliter = new InputFilter();
        $fliter->add($accountFilter->get('password'))
            ->add($accountFilter->get('re_password'));

        $form->setInputFilter($fliter);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $user->setCheckCode(null);
                $user->setPassword(md5($form->get('password')
                    ->getValue()));
                $em->persist($user);
                $em->flush();
                $viewModel->setTemplate('user/sign/reset-password-success.phtml');
                $this->getServiceLocator()
                    ->get('Zend\Log')
                    ->info($user->getUsername() . ' reset password');
                $this->flashMessenger()->addSuccessMessage('更改密碼成功!');
                return $this->redirect()->refresh();
            }
        }

        $viewModel->setVariable('form', $form);
        return $viewModel;
    }*/

    public function sendMailAction()
    {
        if (count($this->flashMessenger()->getMessages()) == 0)
            return $this->redirect()->toRoute('user/lostpassword');
        else {
            $viewModel = new ViewModel();
            $viewModel->setVariable('flashMessages', $this->flashMessenger()
                ->getMessages());
            return $viewModel;
        }
    }

    public function errorAction()
    {
        if (!$this->flashMessenger()->getErrorMessages())
            return $this->redirect()->toRoute('home');

        $viewModel = new ViewModel();
        $viewModel->setVariable('errorMessages', $this->flashMessenger()
            ->getErrorMessages());
        return $viewModel;
    }

    public function showImageAction()
    {

        $id = $this->getRequest()->getquery()->get('id');
        $dm = $this->getDocumentManager();
        $kindId = 'user-avatar-' . $id;

        if ($data = $dm->getRepository('Base\Document\Image')->findOneBy(array('kindId' => $kindId))) {
            header('Content-type: ' . $data->getType() . ';');
            echo $data->getFile()->getBytes();
        } else {
            $file = getcwd()."/public/images/blank.png";
            header('Content-type: image/png;');
            echo file_get_contents($file);
        }

    }
}
