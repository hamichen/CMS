<?php

namespace User\Controller;

use Base\Controller\BaseController;
use Base\Entity\User;
use User\Form\AccountFIlter;
use User\Form\LoginFilter;
use User\Form\LoginForm;
use User\Form\LostPasswordForm;
use User\Form\RegisterForm;
use Zend\Captcha\Image as CaptchaImage;
use Zend\Filter\FilterChain;
use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\InputFilter\InputFilter;
use Zend\Mail;
use Zend\Mail\Message;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;
use Zend\Math\Rand;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

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
            $this->storage = $this->getServiceManager()->get('Base\Model\AuthStorage');
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
                // If you used another name for the authentication service, change it here
                $authService = $this->getAuthService();//->get('doctrine.authenticationservice.orm_default');
                /** @var  $adapter \DoctrineModule\Authentication\Adapter\ObjectRepository */
                $adapter = $authService->getAdapter();
                $adapter->setIdentity($username);
                $adapter->setCredential($password);


                $em = $this->getEntityManager();
                /** @var  $user \Base\Entity\User */
                $user = $em->getRepository('Base\Entity\User')->findOneBy(array(
                    'username' => $form->get('username')
                        ->getValue()
                ));


                $authResult = $authService->authenticate();

                if ($authResult->isValid()) {
                    if (!$user->getIsActive()) {
                        $session = new \Zend\Session\Container('user');
                        $session->getManager()->destroy();
                        $errorMessage = '您的帳號尚未驗證或已離開本單位，無法登入';
                    } elseif ($user->getRole() == '註冊會員') {
                        $errorMessage = '您註冊的帳號尚未啟用，若有疑問請洽管理人員處理。';
                    }
                    else {
                        if ($ap = $request->getPost()->get('ap'))
                            $redirect = str_replace(':', '/', $ap);
                        else
                            $redirect = '';


                        $httpIp = $this->getRequest()->getServer('REMOTE_ADDR');

                        $user->setPassword(null); // 清除密碼欄位
                        $this->getAuthService()
                            ->getStorage()
                            ->write($user);

                        $this->getServiceManager()
                            ->get('Log')
                            ->info($request->getPost('username') . ' login from ' . $httpIp);


                        $base = $this->request->getBasePath();
                        $response = $this->getResponse();
                        $response->setStatusCode(302);
                        $response->getHeaders()->addHeaderLine('Location', $base . '/' . $redirect);
                        return $response;
                    }

                } else {
                    $errorMessage = '帳號密碼有誤，請檢查!';
                }
            }
        }

        $viewModel->setVariable('errorMessage', $errorMessage);
        $viewModel->setVariable('form', $form);
        return $viewModel;
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

        $data['id'] = $captcha->generate();
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
        $this->getAuthService()->clearIdentity();
        $httpIp = $this->getRequest()->getServer('REMOTE_ADDR');
        $this->getServiceManager()
            ->get('Log')
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
                $em = $this->getServiceManager()->get('doctrine.entitymanager.orm_default');
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
     * 會員註冊
     * @return \Zend\Http\Response|ViewModel
     */
    public function registerAction()
    {
        $form = $this->_getRegisterForm();
        $em = $this->getEntityManager();
        $viewModel = new ViewModel();
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);
            if ($form->isValid()) {
                $is_ok = true;
                $data = $form->getData();
                // 檢查帳號是否重複
                $res = $em->getRepository('Base\Entity\User')->find($data['username']);
                if ($res) {
                    $is_ok = false;
                    $form->get('username')->setMessages(['account_double' => '帳號已被註冊了']);
                }
                // 檢查密碼是否相同
                if ($data['password'] != $data['re_password']) {
                    $is_ok = false;
                    $form->get('re_password')->setMessages(['account_double' => '兩次密碼不同']);
                }

                if ($is_ok) {
                    $userRes = new User();
                    $userRes->setName($data['name']);
                    $userRes->setRole('註冊會員');
                    $userRes->setIsActive(0);
                    $userRes->setMrUnit($em->getReference('Base\Entity\MrUnit', $data['mr_unit_id']));
                    $userRes->setUsername($data['username']);
                    $userRes->setEmail($data['email']);
                    $userRes->setJobTitle($data['job_title']);
                    $userRes->setPhone($data['phone']);
                    $userRes->setMobile($data['mobile']);
                    $userRes->setPassword(md5($data['password']));
                    $mailVerify = Rand::getString(30, 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890');
                    $userRes->setMailVerify($mailVerify);
                    $em->persist($userRes);
                    $em->flush();

                    $uri = $this->getRequest()->getUri();
                    $baseUri = sprintf('%s://%s', $uri->getScheme(), $uri->getHost());

                    $viewModel->setVariable('baseUri', $baseUri);
                    $viewModel->setVariable('data', $userRes);

                    $viewModel->setTemplate('user/sign/send-mail-verify.twig');

                    $viewRender = $this->getServiceManager()->get('ZfcTwigRenderer');
                    $messageBody = $viewRender->render($viewModel);

                    // 寄確認信
                    // Setup SMTP transport
                    $transport = new SmtpTransport();
                    $config = $this->getServiceManager()->get('config');

                    $options = new SmtpOptions($config['smtp_options']);
                    $transport->setOptions($options);

                    $html = new MimePart($messageBody);
                    $html->setType('text/html');
                    $body = new MimeMessage();
                    $body->addPart($html);

                    $template = new Message();
                    $template->setEncoding('UTF-8');

                    $template->addFrom('norelay@your-company.tw', '會員通知');
                    $template->addTo($data['email'], $data['name']);
                    $template->setSubject('會員帳號啟用認證通知');
                    $template->setBody($body);
                    try{
                        $transport->send($template);
                    }
                    catch ( \Exception $e){
                        echo $e->getMessage();
                        die ('email 傳送失敗');
                    }


                    $this->flashMessenger()->addInfoMessage($data['name'] . ' 註冊成功！！');

                    // 導至成功畫面
                    return $this->redirect()->toUrl('/user/register-success');
                }
            }
        }

        $viewModel->setVariable('form', $form);
        return $viewModel;

    }


    public function registerSuccessAction()
    {

    }

    /**
     * Email 認證
     * @return ViewModel
     */
    public function mailVerifyAction()
    {
        $id = $this->params()->fromRoute('id');
        $em = $this->getEntityManager();
        $filter = new FilterChain();
        $filter->attach(new StripTags());
        $filter->attach(new StringTrim());
        $value = $filter->filter($id);
        /** @var  $res \Base\Entity\User */
        $res = $em->getRepository('Base\Entity\User')->findOneBy(['mail_verify' => $value, 'is_active' => 0]);
        if ($res) {
            $res->setIsActive(1);
            $em->persist($res);
            $em->flush();
        }
        $viewModel = new ViewModel();
        $viewModel->setVariable('data', $res);
        return $viewModel;

    }


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
            $file = getcwd() . "/public/images/blank.png";
            header('Content-type: image/png;');
            echo file_get_contents($file);
        }

    }

    /**
     * 取出登入表單
     * @return LoginForm
     */
    protected function _getForm()
    {
        $form = new LoginForm();

        $captchaConfig = $this->getServiceManager()->get('config')['captcha-image'];
        $dirData = './data';
        $urlCaptcha = '/captcha/';
        $captchaImage = new CaptchaImage($captchaConfig);
        $captchaImage->setImgDir($dirData . '/captcha');
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

        return $form;
    }


    /**
     * 取出註冊表單
     * @return RegisterForm
     */
    protected function _getRegisterForm()
    {
        $form = new RegisterForm();
        $em = $this->getEntityManager();

        $arr = [];
        $qb = $em->createQueryBuilder()
            ->select('u.id, u.short_name')
            ->from('Base\Entity\MrUnit', 'u')
            ->where('u.is_on=1')
            ->andWhere('u.is_sso_login IS NULL or u.is_sso_login=0 ')
            ->orderBy('u.rank')
            ->getQuery()
            ->getArrayResult();

        foreach ($qb as $value)
            $arr[$value['id']] = $value['short_name'];

        $form->get('mr_unit_id')->setValueOptions($arr);

        $captchaConfig = $this->getServiceManager()->get('config')['captucha-image'];
        $dirData = './data';
        $urlCaptcha = '/captcha/';
        $captchaImage = new CaptchaImage($captchaConfig);
        $captchaImage->setImgDir($dirData . '/captcha');
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

        return $form;
    }
}
