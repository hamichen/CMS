<?php

namespace User\Model;

use Zend\Form\Annotation;

/**
 * @Annotation\Name("user")
 * @Annotation\Hydrator("Zend\Stdlib\Hydrator\ObjectProperty")
 */

class User
{
	/**
	 * @Annotation\Filter({"name":"StringTrim"})
	 * @Annotation\Validator({"name":"StringLength", "options":{"min":1, "max":25}})
	 * @Annotation\Validator({"name":"Regex", "options":{"pattern":"/^[a-zA-Z][a-zA-Z0-9_-]{0,24}$/"}})
	 * @Annotation\Attributes({"type":"text"})
	 * @Annotation\Options({"label":"登入帳號:"})
	 * 
	 */
	public $username;

	/**
	 * @Annotation\Type("Zend\Form\Element\Password")	 
	 * @Annotation\Filter({"name":"StripTags"})	 
	 * @Annotation\Options({"label":"登入密碼:"})
	 * 
	 */
	public $password;

	/**
	 * @Annotation\Type("Zend\Form\Element\Checkbox")
	 * @Annotation\Options({"label": "記住我?"})	 
	 */
	public $rememberme;

	/**
	 * @Annotation\Type("Zend\Form\Element\Submit")
	 * @Annotation\Attributes({"value":"登入"})
	 */
	public $submit;
	
	
}
