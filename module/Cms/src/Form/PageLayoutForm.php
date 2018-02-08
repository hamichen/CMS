<?php

namespace Cms\Form;


use Zend\Form\Form;

class PageLayoutForm extends Form
{

    public function __construct()
    {
        parent::__construct('pageLayoutForm');
        $this->add(array(
            'name' => 'title',
            'type' => 'Zend\Form\Element\Text',
            'options' => array(
                'label' => '版面名稱'
            )
        ));
        $this->add(array(
            'name' => 'content',
            'type' => 'Zend\Form\Element\Textarea',
            'options' => array(
                'label' => '版面內容'
            ),

        ));
        $this->add(array(
            'name' => 'order_id',
            'type' => 'Zend\Form\Element\Hidden'

        ));
        $this->add(array(
            'name' => 'id',
            'type' => 'Zend\Form\Element\Hidden'

        ));
    }
}
