<?php

namespace Cms\Form;


use Zend\Form\Form;

class PageUrlForm extends Form
{

    public function __construct()
    {
        parent::__construct('pageTextForm');
        $this->add(array(
            'name' => 'title',
            'type' => 'Zend\Form\Element\Text',
            'options' => array(
                'label' => '連結標題'
            )
        ));
        $this->add(array(
            'name' => 'url',
            'type' => 'Zend\Form\Element\Url',
            'options' => array(
                'label' => '連結'
            )
        ));
        $this->add(array(
            'name' => 'link_menu',
            'type' => 'Zend\Form\Element\Select',
            'options' => array(
                'label' => '連接到內部選單',
                'empty_option' => ''
            )
        ));
        $this->add(array(
            'name' => 'term',
            'type' => 'Zend\Form\Element\Radio',
            'options' => array(
                'label' => '分類'
            ),

        ));
        $this->add(array(
            'name' => 'url_target',
            'type' => 'Zend\Form\Element\Select',
            'options' => array(
                'label' => '開啟方式',
                'value_options' => array(
                    '' => '相同視窗',
                    '_blank' => '另開新視窗'
                ),
            ),

        ));
        $this->add(array(
            'name' => 'upload_file',
            'type' => 'Zend\Form\Element\File',
            'options' => array(
                'label' => '選擇標題圖檔'
            ),
            'attributes' => array(
                'id' => 'files'
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
            'name' => 'is_published',
            'type' => 'Zend\Form\Element\Radio',
            'options' => array(
                'label' => '是否發布',
                'value_options' => array(
                    '1' => '是',
                    '0' => '否'
                )
            ),
            'attributes' => array(
                'value' => '1'
            )
        ));
        $this->add(array(
            'name' => 'summary',
            'type' => 'Zend\Form\Element\Textarea',
            'options' => array(
                'label' => '摘要'
            )
        ));
        $this->add(array(
            'name' => 'id',
            'type' => 'Zend\Form\Element\Hidden'

        ));
    }
}