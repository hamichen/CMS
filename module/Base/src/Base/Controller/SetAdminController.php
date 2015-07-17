<?php
/**
 * Created by PhpStorm.
 * User: hami
 * Date: 2015/7/17
 * Time: 下午 11:56
 */

namespace Base\Controller;


class SetAdminController extends  BaseController
{

    public function runAction()
    {
        $username = $this->params()->fromRoute('username');
        $password = $this->params()->fromRoute('password');
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder()
            ->select('u')
            ->from('Base\Entity\User', 'u')
            ->where('u.username=:username')
            ->setParameter('username', $username)
            ->getQuery()
            ->getResult();
        if (count($qb))
            $userRes = $qb[0];
        else
        {
            $userRes = new \Base\Entity\User();
            $userRes->setUsername($username);
            $userRes->setDisplayName('管理者');
        }

        $userRes->setPassword(\Zend\Ldap\Attribute::createPassword($password));
        $userRes->setRole('admin');
        $em->persist($userRes);
        $em->flush();

        echo "\n $username 設定成功 \n ";

    }
}