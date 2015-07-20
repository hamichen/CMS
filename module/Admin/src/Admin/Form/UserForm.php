<?php
/**
 * Created by PhpStorm.
 * User: hami
 * Date: 2015/7/2
 * Time: 下午 9:01
 */

namespace Admin\Form;


use Zend\Form\Form;

class UserForm extends Form
{

    public function __construct()
    {
        parent::__construct('userForm');
        $this->add(array(
            'name' => 'username',
            'type' => 'Zend\Form\Element\Text',
            'options' => array(
                'label' => '登入帳號',
            )

        ));
        $this->add(array(
            'name' => 'password',
            'type' => 'Zend\Form\Element\Password',
            'options' => array(
                'label' => '登入密碼',
            )

        ));
        $this->add(array(
            'name' => 'display_name',
            'type' => 'Zend\Form\Element\Text',
            'options' => array(
                'label' => '顯示名稱',
            )
        ));
        $this->add(array(
            'name' => 'role',
            'type' => 'Zend\Form\Element\Radio',
            'options' => array(
                'label' => '角色'
            )

        ));
        $this->add(array(
            'name' => 'id',
            'type' => 'Zend\Form\Element\Hidden'

        ));
    }
}