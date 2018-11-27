<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 2018/11/27
 * Time: 下午 02:09
 */

namespace Admin\Form;


use Zend\Form\Element\Hidden;
use Zend\Form\Element\Submit;
use Zend\Form\Element\Text;
use Zend\Form\Element\Textarea;
use Zend\Form\Form;

class NoteForm extends Form
{
    public function __construct($name = null, array $options = [])
    {
        parent::__construct($name, $options);

        $this->add([
            'name' => 'title',
            'type' => Text::class,
            'options' => [
                'label' => '標題'
            ]
        ]);

        $this->add([
            'name' => 'content',
            'type' => Textarea::class,
            'options' => [
                'label' => '內容'
            ]
        ]);

        $this->add([
            'name' => 'submit',
            'type' => Submit::class,
            'attributes' => [
                'class' => 'btn btn-primary',
                'value' => '傳送'
            ]

        ]);

        $this->add([
            'name' => 'id',
            'type' => Hidden::class
        ]);
    }


}