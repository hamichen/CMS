<?php
namespace Base\Entity;

use Doctrine\ORM\EntityRepository;

class PageRepository extends EntityRepository
{

    public function getPageKindArray()
    {
        return array(
            'text' => '文字',
            'file' => '檔案',
            'url' => '連結'
        );
    }

}