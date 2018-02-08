<?php
namespace User\Form;
use Zend\Form\Form;
use Zend\Form\Element;
class LostPasswordForm extends Form
{
    public function __construct()
    {
        parent::__construct();
        $this->setName('lostPasswordForm');
        $this->setAttribute('method', 'post');

        // 		$this->add(array(
        // 				'name'	=>	'username',
        // 				'type'	=>	'Zend\Form\Element\Text',
        // 				'attributes' => array(
        // 						'placeholder' => '請輸入您的帳號',
        // 				),
        // 				'options'	=>	array(
        // 						'label'	=>	'您註冊的帳號'
        // 						),
        // 			));

        $this->add(array(
                'name'	=>	'email',
                'type'	=>	'Zend\Form\Element\Email',
                'attributes'	=>	array(
                        'placeholder'	=>	'請輸入您註冊的電子郵件'
                ),
                'options'	=>	array(
                        'label'	=>	'您註冊的電子郵件'
                ),

        ));
        $this->add(new Element\Csrf('csrf'));


        $this->add(array(
                'name'	=>	'submit',
                'attributes' => array(
                        'type' => 'submit',
                        'value' => '寄送系統預設密碼到我的信箱'
                ),

        ));
    }
}
