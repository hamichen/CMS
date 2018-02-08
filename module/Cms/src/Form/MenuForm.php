<?php
namespace Cms\Form;

use Zend\Form\Form;

class MenuForm extends Form
{

    public function __construct()
    {
        parent::__construct('menuForm');
        $this->add(array(
            'name' => 'name',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'placeholder' => '請輸入選單名稱',
            ),
            'options' => array(
                'label' => '選單名稱'
            ),
        ));
        $this->add(array(
            'name' => 'parent_id',
            'type' => 'Zend\Form\Element\Select',
            'options' => array(
                'label' => '上層選單',
            ),
        ));
        $this->add(array(
            'name' => 'layout',
            'type' => 'Zend\Form\Element\Select',
            'options' => array(
                'label' => '呈現類型',
            ),
        ));
        $this->add(array(
            'name' => 'is_display',
            'type' => 'Zend\Form\Element\Radio',
            'options' => array(
                'label' => '是否顯示在選單上',
                'value_options' => array(
                    '1' => '顯示',
                    '0' => '不顯示'
                )
            ),
            'attributes' => array(
                'value' => 1
            )
        ));
        $this->add(array(
            'name' => 'order_id',
            'type' => 'Zend\Form\Element\Text',
            'options' => array(
                'label' => '排序'
            ),
        ));
        $this->add(array(
            'name' => 'url',
            'type' => 'Zend\Form\Element\Text',
            'options' => array(
                'label' => '連結',
                'description' => '例: http://tw.yahoo.com',
            ),
        ));
        $this->add(array(
            'name' => 'target',
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
            'name' => 'max_records',
            'type' => 'Zend\Form\Element\Text',
            'options' => array(
                'label' => '每頁顯示文章筆數(5-50筆之間)',
            ),
            'attributes' => array(
                'value' => 10
            )
        ));

        $this->add(array(
            'name' => 'order_kind',
            'type' => 'Zend\Form\Element\Select',
            'options' => array(
                'label' => '文章排序',
                'value_options' => array(
                    "desc" => "依時間後先順序",
                    "asc" => "依時間先後排序",
                    "id_asc" => "依文章編號升冪排序",
                    "id_desc" => "依文章編號降冪排序",
                    "custom_asc" => "自訂升冪排序",
                    "custom_desc" => "自訂降冪排序"
                )
            ),
        ));

        $this->add(array(
            'name' => 'term',
            'type' => 'Zend\Form\Element\Text',
            'options' => array(
                'label' => '自訂文章類別,類別與類別之間以逗號隔開',
            ),
        ));

        $this->add(array(
            'name' => 'memo',
            'type' => 'Zend\Form\Element\Textarea',
            'options' => array(
                'label' => '選單說明'
            )
        ));

        $this->add(array(
            'name' => 'title_limit',
            'type' => 'Zend\Form\Element\Radio',
            'options' => array(
                'label' => '限制修改標題',
                'value_options' => array(
                    '0' => '否',
                    '1' => '是'

                )
            ),
            'attributes' => array(
                'value' => 0
            )
        ));

        $this->add(array(
            'name'=> 'id',
            'type' => 'Zend\Form\Element\Hidden'
        ));


    }
}