<?php

namespace Base\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** @ODM\Document (collection="image_thumb")*/

class ImageThumb
{
	/** @ODM\Id */
	private $id;

	/** @ODM\Field */
	private $name;

	/** @ODM\File */
	private $file;
	
	/** @ODM\Date */
	private $uploadDate;

	/** @ODM\Field */
	private $length;

	/** @ODM\Field */
	private $chunkSize;

	/** @ODM\Field */
	private $md5;

	/** @ODM\Field */
	private $type;
	
	/** @ODM\Field */
	private $sizeType;
	
	/** @ODM\ReferenceOne(targetDocument="Image") */
	private $image;
	

	public function getId()
	{
		return $this->id;
	}

	public function setName($name)
	{
		$this->name = $name;
	}

	public function getName()
	{
		return $this->name;
	}

	public function getFile()
	{
		return $this->file;
	}

	public function setFile($file)
	{
		$this->file = $file;
	}
	
	public function setType($type)
	{
		$this->type = $type;
	}
	public function getType()
	{
		return $this->type;
	}
	public function getChunkSize()
	{
		return $this->chunkSize;
	}

	public function setChunkSize($chunkSize)
	{
		$this->chunkSize = $chunkSize;
	}
	public function setPhoto(Photo $photo)
	{
		$this->photo = $photo;
	}

	public function getPhoto()
	{
		return $this->photo;
	}
	
	public function setSizeType($sizeType)
	{
		$this->sizeType = $sizeType;
	}
	
	public function getSizeType()
	{
		return $this->sizeType;
	}
	
	public function setImage(Image $image)
	{
		$this->image = $image;	
	}
	
	public function getImage()
	{
		return $this->image;
	}
	
	public function getUploadDate()
	{
		return $this->uploadDate;
	}
	
	public function getLength()
	{
		return $this->length;
	}
}