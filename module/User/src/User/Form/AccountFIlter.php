<?php
namespace User\Form;
use Zend\InputFilter\InputFilter;

class AccountFIlter extends InputFilter
{

    public function __construct()
    {
        $this->add(array(
                'name'	=>	'username',
                'required'	=>	true,
                'filters'	=>array(
                        array('name' => 'StripTags'),
                        array('name' => 'StringTrim'),
                ),
        ));
        $this->add(array(
                'name'	=>	'email',
                'required'	=>	true,
                'filters'	=>array(
                        array('name' =>	'StripTags'),
                        array('name' => 'StringTrim')
                ),
        ));
        $this->add(array(
                'name'	=>	'password',
                'required'	=>	true,
                'filters'	=>array(
                        array('name' =>	'StripTags'),
                        array('name' => 'StringTrim')
                ),
                'validators' => array(
                        array('name'=>'StringLength',
                                'options' => array(
                                        'min' 	=> 6,
                                        'max'	=> 20,
                                ),
                        ),
                ),
        ));
        $this->add(array(
                'name'	=>	're_password',
                'required'	=>	true,
                'filters'	=>array(
                        array('name' =>	'StripTags'),
                        array('name' => 'StringTrim')
                ),
                'validators' => array(
                        array(
                                'name' => 'Identical',
                                'options' => array(
                                        'token' => 'password',
                                        // 										'messages' => array(
                                                // 												\Zend\Validator\Identical::NOT_SAME => 'Confirm password does not match!'    ),
                                ),
                        ),
                ),
        ));

        $this->add(array(
                'name'	=>	'first_name',
                'required'	=>	true,
                'filters'	=>array(
                        array('name' =>	'StripTags'),
                        array('name' => 'StringTrim')
                ),
        ));
        $this->add(array(
                'name'	=>	'last_name',
                'required'	=>	true,
                'filters'	=>array(
                        array('name' =>	'StripTags'),
                        array('name' => 'StringTrim')
                ),
        ));
        $this->add(array(
                'name'	=>	'birthday',
                'filters'	=>array(
                        array('name' =>	'StripTags'),
                        array('name' => 'StringTrim')
                ),

        ));
        $this->add(array(
                'name'	=>	'sex',
                'filters'	=>array(
                        array('name' =>	'StripTags'),
                        array('name' => 'StringTrim')
                ),
        ));
    }
}
