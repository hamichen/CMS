<?php
namespace Base\Document;
use Doctrine\Common\Collections\ArrayCollection;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** @ODM\Document */
class Image
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
    private $kindId;

    /** @ODM\Field */
    private $sourceId;

    /** @ODM\EmbedOne(targetDocument="ImageThumb") */
    private $thumb;

    /** @ODM\EmbedMany(targetDocument="ImageTags") */
    public $tags = array();
    
    
    public function __construct()
    {
    	$this->tags = new ArrayCollection();
    	
    }
    
    
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

    public function setKindId($kindId)
    {
        $this->kindId = $kindId;
    }
    public function getKindId()
    {
        return $this->kindId;
    }

    public function setSourceId($id)
    {
        $this->sourceId = $id;
    }

    public function getSourceId()
    {
        return $this->sourceId;
    }


    public function getUploadDate()
    {
    	return $this->uploadDate;
    }
    
    public function getLength()
    {
    	return $this->length;
    }
    
    public function addTags(ImageTags $tags)
    {
    	
    	$this->tags[]  = $tags;
    }
    
    
    public function getTags()
    {
    	
    	return $this->tags;
    }
}

