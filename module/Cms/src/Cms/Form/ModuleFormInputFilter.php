<?php
namespace Cms\Form;

use Zend\InputFilter\InputFilter;

class ModuleFormInputFilter extends InputFilter
{

	public function __construct()
	{
		
		$this->add(array(
				'name'          => 'module_name',
				'required'      => true,
				'filters'  => array(
						array('name' => 'StripTags'),
						array('name' => 'StringTrim'),
				)							
			));
		$this->add(array(
				'name'          => 'display_name',
				'required'      => true,
				'filters'  => array(
						array('name' => 'StripTags'),
						array('name' => 'StringTrim'),
				)
		));
		
	}
}