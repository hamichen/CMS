<?php
/**
 *
 *
 * @author    sfs teams <zfsfs.team@gmail.com>
 * @copyright 2010-2017 (http://www.sfs.tw)
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 * @link      http://www.sfs.tw
 * Date: 2017/8/10
 * Time: 下午 4:58
 */

namespace User\Form;


use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

class UserForm extends Form
{
    public function __construct($name = null, array $options = [])
    {
        parent::__construct($name, $options);

        $this->add([
            'name' => 'display_name',
            'type' => 'Zend\Form\Element\Text',
            'options' => [
                'label' => '顯示名稱',
            ]
        ]);

        $this->add([
            'name' => 'job_title',
            'type' => 'Zend\Form\Element\Text',
            'options' => [
                'label' => '職稱',
            ],
            'attributes' => [
                'required' => true
            ]
        ]);

        $this->add([
            'name' => 'email',
            'type' => 'Zend\Form\Element\Text',
            'options' => [
                'label' => 'Email'
            ],
            'attributes' => [
                'required' => true
            ]
        ]);

        $this->add([
            'name' => 'phone',
            'type' => 'Zend\Form\Element\Text',
            'options' => [
                'label' => '連絡電話'
            ],
            'attributes' => [
                'required' => true
            ]
        ]);

        $this->add([
            'name' => 'phone_ext',
            'type' => 'Zend\Form\Element\Text',
            'options' => [
                'label' => '分機'
            ]
        ]);

        $this->add([
            'name' => 'mobile',
            'type' => 'Zend\Form\Element\Text',
            'options' => [
                'label' => '手機'
            ]
        ]);

        $this->addInputFilter();

    }

    public function addInputFilter()
    {
        $inputFilter = new InputFilter();
        $this->setInputFilter($inputFilter);
        $inputFilter->add([
            'name' => 'job_title',
            'required' => true,
            'filters' => [
                ['name' => 'StringTrim'],
                ['name' => 'StripTags'],
                ['name' => 'StripNewlines'],
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'min' => 1,
                        'max' => 20
                    ],
                ],
            ],
        ]);
        $inputFilter->add([
                'name' => 'email',
                'required' => true,
                'validators' => [
                    [
                        'name' => 'StringLength',
                        'options' => [
                            'encoding' => 'UTF-8',
                            'min' => 2,
                            'max' => 100,
                        ],
                    ],
                    [
                        'name' => 'EmailAddress',
                    ],
                ]
            ]
        );
        $inputFilter->add([
                'name' => 'phone',
                'required' => true,

            ]
        );

    }


}