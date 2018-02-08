<?php


namespace Cms\Form;


use Zend\Form\Form;

class PageFileForm extends Form
{
    public function __construct()
    {
        parent::__construct('fileForm');
        $this->add(array(
            'name' => 'title',
            'type' => 'Zend\Form\Element\Text',
            'options' => array(
                'label' => '標題'
            )
        ));

        $this->add(array(
            'name' => 'upload_file[]',
            'type' => 'Zend\Form\Element\File',
            'options' => array(
                'label' => '選擇上傳的檔案(可多選)'
            ),
            'attributes' => array(
                'id' => 'files'
            )
        ));
        $this->add(array(
            'name' => 'content',
            'type' => 'Zend\Form\Element\Textarea',
            'options' => array(
                'label' => '檔案說明'
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
            'name' => 'tags',
            'type' => 'Zend\Form\Element\Text',
            'options' => array(
                'label' => '關鍵字(多個關鍵字時以(,)逗號隔開)'
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
            'name' => 'id',
            'type' => 'Zend\Form\Element\Hidden'

        ));
    }

}