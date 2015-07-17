<?php
/**
 * Created by PhpStorm.
 * User: hami
 * Date: 2014/8/1
 * Time: 下午 10:53
 */

namespace Base\Entity;


use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{

    public function getRoleArray()
    {
        return array(
            'teacher' => '教師',
            'admin' => '管理者'
        );
    }

} 