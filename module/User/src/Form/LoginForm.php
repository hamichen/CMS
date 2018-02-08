<?php
namespace User\Form;

use Zend\Form\Element\Csrf;
use Zend\Form\Form;


class LoginForm extends Form
{
    /*
     * (non-PHPdoc) @see \Zend\Form\Fieldset::__construct()
     */
    public function __construct($name = null, array $options = array())
    {
        // TODO: Auto-generated method stub
        parent::__construct('loginForm');
        $this->setAttribute('class', 'form-signin');
        $this->setAttribute('role', 'form');
        $this->setAttribute('autocomplete','off');
        $this->add(array(
            'name' => 'username',
            'type' => 'Zend\Form\Element\Text',
            'options' => array(
                'label' => '登入帳號',

            ),
            'attributes' => array(
                'placeholder' => '請輸入帳號',
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'password',
            'options' => array(
                'label' => '密碼'
            ),
            'attributes' => array(
                'type' => 'password',
                'placeholder' => '請輸入您的密碼',
                'class' => 'form-control'
            )
        ));

        $this->add([
            'type' => Csrf::class,
            'name' => 'csrf',
            'options' => array(
                'csrf_options' => array(
                    'timeout' => 600
                )
            )]);

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'value' => '登入',
                'class' => 'btn btn-lg btn-primary btn-block'
            )
        ));
    }
}
