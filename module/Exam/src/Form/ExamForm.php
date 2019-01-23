<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 2019/1/23
 * Time: 下午 01:15
 */

namespace Exam\Form;


use Zend\Form\Element\File;
use Zend\Form\Element\Hidden;
use Zend\Form\Element\Text;
use Zend\Form\Element\Textarea;
use Zend\Form\Form;

class ExamForm extends Form
{

    public function __construct($name = null, array $options = [])
    {
        parent::__construct($name, $options);

        $this->add([
            'name' => 'subject',
            'type' => Text::class,
            'options' => [
                'label' => '作業名稱'
            ]
        ]);
        $this->add([
            'name' => 'open_time',
            'type' => Text::class,
            'options' => [
                'label' => '作業開放時間'
            ]
        ]);
        $this->add([
            'name' => 'close_time',
            'type' => Text::class,
            'options' => [
                'label' => '作業結束時間'
            ]
        ]);
        $this->add([
            'name' => 'memo',
            'type' => Textarea::class,
            'options' => [
                'label' => '作業開放時間'
            ]
        ]);
        $this->add([
            'name' => 'stu_img',
            'type' => File::class,
            'options' => [
                'label' => ' file'
            ]
        ]);

        $this->add([
            'name' => 'id',
            'type' => Hidden::class

        ]);

        $this->addInputFilter();
    }


    public function addInputFilter()
    {
        $inputFilter = $this->getInputFilter();
        $inputFilter->add([
            'name' => 'subject',
            'required' => true,
            'filters'  => [
                ['name' => 'StringTrim'],
                ['name' => 'StripTags'],
                ['name' => 'StripNewlines'],
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'min' => 6,
                        'max' => 128
                    ],
                ],
            ],
        ]);
    }
}