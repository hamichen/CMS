<?php

namespace User\Form;

use Zend\Form\Element\Csrf;
use Zend\Form\Element\Email;
use Zend\Form\Element\Password;
use Zend\Form\Element\Select;
use Zend\Form\Element\Text;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

class RegisterForm extends Form
{

    /**
     * RegisterForm constructor.
     */
    public function __construct()
    {
        parent::__construct('register_form');
        $this->setAttribute('method', 'post');

        $this->add([
            'name' => 'username',
            'type' => Text::class,
            'options' => [
                'label' => '登入帳號',
            ],
            'attributes' => [
                'placeholder' => '請勿以身分證做為帳號',
                'required' => true,
            ],
        ]);

        $this->add([
            'name' => 'mr_unit_id',
            'type' => Select::class,
            'options' => [
                'label' => '任職單位',
                'empty_option' => '請選擇單位',
                'value_options' => []
            ],
            'attributes' => [
                'required' => true,
            ]

        ]);

        $this->add([
            'name' => 'job_title',
            'type' => Text::class,
            'options' => [
                'label' => '職稱',
            ],
            'attributes' => [
                'required' => true,
            ]

        ]);

        $this->add([
            'name' => 'name',
            'type' => Text::class,
            'options' => [
                'label' => '姓名',
            ],
            'attributes' => [
                'required' => true,
            ]

        ]);
        $this->add([
            'name' => 'phone',
            'type' => Text::class,
            'options' => [
                'label' => '連絡電話(公)',
            ],
            'attributes' => [
                'required' => true,
            ]

        ]);
        $this->add([
            'name' => 'mobile',
            'type' => Text::class,
            'options' => [
                'label' => '手機',
            ]

        ]);
        $this->add([
            'type' => Email::class,
            'name' => 'email',
            'options' => [
                'label' => '電子郵件',
            ],
            'attributes' => [
                'placeholder' => '請留公務使用之電子郵件，錯誤的電子郵件將無法完成驗證',
                'required' => true,
            ],
        ]);
        $this->add([
            'name' => 'password',
            'type' => Password::class,
            'options' => [
                'label' => '密碼',
            ],
            'attributes' => [
                'type' => 'password',
                'placeholder' => '請輸入您的密碼',
                'required' => true,
            ],
        ]);
        $this->add(array(
            'name' => 're_password',
            'type' => Password::class,
            'options' => array(
                'label' => '確認密碼',
            ),
            'attributes' => array(
                'type' => 'password',
                'placeholder' => '再輸入一次密碼',
                'required' => true,
            ),
        ));

        $this->add(new Csrf('security'));


        $this->addInputFilter();
    }

    public function addInputFilter()
    {
        $inputFilter = new InputFilter();
        $this->setInputFilter($inputFilter);
        $inputFilter->add([
            'name' => 'username',
            'required' => true,
            'filters' => [
                ['name' => 'StringTrim'],
                ['name' => 'StripTags'],
                ['name' => 'StripNewlines'],
            ],

        ]);
        $inputFilter->add([
            'name' => 'name',
            'required' => true,
            'filters' => [
                ['name' => 'StringTrim'],
                ['name' => 'StripTags'],
                ['name' => 'StripNewlines'],
            ],
        ]);
        $inputFilter->add([
            'name' => 'job_title',
            'required' => true
        ]);
        $inputFilter->add([
            'name' => 'password',
            'required' => true,
            'validators' => [
                ['name' => 'StringLength',
                    'options' => [
                        'min' => 6
                    ]
                ]
            ]
        ]);
        $inputFilter->add([
            'name' => 're_password',
            'required' => true
        ]);

        $inputFilter->add([
            'name' => 'phone',
            'required' => true
        ]);
    }

}

