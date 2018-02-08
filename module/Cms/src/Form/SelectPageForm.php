<?php
namespace Cms\Form;

use Zend\Form\Form;

class SelectPageForm extends Form
{

    public function __construct()
    {

        parent::__construct('pageForm');
        $this->add(array(
            'name' => 'menu_id',
            'type' => 'Zend\Form\Element\Select',
            'options' => array(
                'label' => '選單名稱',
            ),

        ));

       /* $this->add(array(
            'name' => 'kind',
            'type' => 'Zend\Form\Element\Select',
            'options' => array(
                'label' => '文件類別',
                'empty_option' => '請選擇新增文件類別',
                'value_options' => array(
                    'text' => '文字',
                    'file' => '檔案',
                    'link' => '連結'
                )
            )

        ));*/

    }
}
