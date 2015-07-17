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