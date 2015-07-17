<?php
namespace User\Form;
use Zend\Form\Element;
use Zend\Captcha\Image as CaptchaImage;
use Zend\Form\Form;

class RegisterForm extends Form{

    public function __construct($urlcaptcha = null)
    {
        parent::__construct('User Register Form');
        $this->setAttribute('method', 'post');

        $this->add(array(
                'name' => 'username',
                'type' => 'Zend\Form\Element\Text',
                'options' => array(
                        'label' => '帳號',
                ),
                'attributes' => array(
                        'type'  => 'text',
                ),
        ));

        $this->add(array(
                'name' => 'first_name',
                'type' => 'Zend\Form\Element\Text',
                'options' => array(
                        'label' => '姓',
                )

        ));

        $this->add(array(
                'name' => 'last_name',
                'type' => 'Zend\Form\Element\Text',
                'options' => array(
                        'label' => '名',
                ),
        ));

        $this->add(array(
                'type' => 'Zend\Form\Element\Text',
                'name' => 'birthday',
                'options' => array(
                        'label' => '生日',
                        'description'=>'請輸入西元年月日，例: 2000-1-1'
                ),

        ));

        $this->add(array(
                'name' => 'sex',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                        'label' => '性別',
                        'value_options' => array(
                                ''	=>	'請選擇',
                                'male'	=> '男',
                                'female' => '女'
                        ),
                ),
        ));

        $this->add(array(
                'type' => 'Zend\Form\Element\Email',
                'name' => 'email',
                'options' => array(
                        'label' => '電子郵件',
                ),
        ));
        $this->add(array(
                'name' => 'password',
                'options' => array(
                        'label' => '密碼',
                ),
                'attributes' => array(
                        'type'  => 'password',
                        'placeholder' => '請輸入您的密碼',
                ),
        ));
        $this->add(array(
                'name' => 're_password',
                'options' => array(
                        'label' => '確認密碼',
                ),
                'attributes' => array(
                        'type'  => 'password',
                        'placeholder' => '再輸入一次密碼',
                ),
        ));

        $this->add(new Element\Csrf('security'));


        $dirdata = './data';
        $captchaImage = new CaptchaImage(array(
                'font' => $dirdata.'/fonts/arial.ttf',
                'width' => 200,
                'height' => 80,
                'wordlen' => 5,
                'dotNoiseLevel' => 40,
                'lineNoiseLevel' => 8
        ));
        $captchaImage->setImgDir($dirdata.'/captcha');
        $captchaImage->setImgUrl($urlcaptcha);

        $captcha = new Element\Captcha('captcha');
        $captcha->setCaptcha($captchaImage);
        $captcha->setLabel('請輸入驗證碼');
        $this->add($captcha);

        $this->add(array(
                'name' => 'submit',
                'attributes' => array(
                        'type' => 'submit',
                        'value' => '註冊',
                        'class' => 'btn btn-primary'
                ),

        ));

    }

}

