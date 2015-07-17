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