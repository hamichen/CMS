<?php

namespace Base\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** @ODM\EmbeddedDocument */
class ImageTags
{
	/** @ODM\Id */
	private $id;

	/** @ODM\Field */
	private $name;

	
	public function setName($name)
	{
		$this->name = $name;
	}

	public function getName()
	{
		return $this->name;
	}
		
}