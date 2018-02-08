<?php
namespace Base\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** @ODM\Document */
class NewsFile
{
    /** @ODM\Id */
    private $id;

    /** @ODM\Field(type="string") */
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

    /** @ODM\Field(type="string") */
    private $type;

    /** @ODM\Field(type="int") */
    private $eventId;

    /** @ODM\Field(type="int") */
    private $newsId;


    /** @ODM\Field */
    private $uploadUser;

    /** @ODM\Field(type="string") */
    private $kind;

    /** @ODM\Field(type="string") */
    private $memo;

    public function __construct()
    {

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

    public function getKind()
    {
        return $this->kind;
    }

    public function setKind($kind)
    {
        $this->kind = $kind;
    }

    public function getMemo()
    {
        return $this->memo;
    }

    public function setMemo($memo)
    {
        $this->memo = $memo;
    }

    public function setType($type)
    {
        $this->type = $type;
    }
    public function getType()
    {
        return $this->type;
    }

    public function setNewsId($id)
    {
        $this->newsId = $id;
    }
    public function getNewsId()
    {
        return $this->newsId;
    }


    public function setEventId($id)
    {
        $this->eventId = $id;
    }
    public function getEventId()
    {
        return $this->eventId;
    }


    public function setUploadUser($user)
    {
        $this->uploadUser = $user;
    }

    public function getUploadUser()
    {
        return $this->uploadUser;
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
