<?php

namespace Application\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** @ODM\Document (collection="semester_file")*/

class SemesterFile
{
    /** @ODM\Id */
    private $id;

    /** @ODM\Field */
    private $name;

    /** @ODM\File */
    private $file;

    /** @ODM\Field */
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
    private $semesterId;



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

    public function setSemesterId($semester_id)
    {
        $this->semesterId = $semester_id;
    }

    public function getSemesterId()
    {
        return $this->semesterId;
    }
}