<?php

namespace Cms\Form;


use Zend\Form\Form;

class PageTextForm extends Form
{

    public function __construct()
    {
        parent::__construct('pageTextForm');
        $this->add(array(
            'name' => 'title',
            'type' => 'Zend\Form\Element\Text',
            'options' => array(
                'label' => '文件標題'
            )
        ));
        $this->add(array(
            'name' => 'content',
            'type' => 'Zend\Form\Element\Textarea',
            'options' => array(
                'label' => '文件內容'
            )
        ));
        $this->add(array(
            'name' => 'tags',
            'type' => 'Zend\Form\Element\Text',
            'options' => array(
                'label' => '關鍵字(多個關鍵字時以(,)逗號隔開)'
            )
        ));
        $this->add(array(
            'name' => 'order_id',
            'type' => 'Zend\Form\Element\Text',
            'options' => array(
                'label' => '排序'
            )
        ));
        $this->add(array(
            'name' => 'id',
            'type' => 'Zend\Form\Element\Hidden'

        ));
    }
}