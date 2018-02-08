<?php


namespace Base\Controller;

use Zend\Console\ColorInterface;
use Interop\Container\ContainerInterface;
use Zend\Mvc\Controller\AbstractActionController;


class SetAdminController extends AbstractActionController
{
    /**
     *
     * @var \Zend\ServiceManager\ServiceLocatorInterface
     */
    protected $serviceManager;

    /**
     *
     * @var \Zend\Console\Adapter\AdapterInterface
     */
    protected $console;


    public function __construct(ContainerInterface $container)
    {
        $this->serviceManager = $container;
        $this->console = $this->serviceManager->get('console');
    }

    public function runAction()
    {

        /** @var  $em \Doctrine\Orm\EntityManager */
        $em = $this->serviceManager->get('doctrine.entitymanager.orm_default');
        $console = $this->serviceManager->get('console');
        $username = $this->params()->fromRoute('username');
        $password = $this->params()->fromRoute('password');
        $sql = "select * from user where username='$username'";
        $res = $em->getConnection()->fetchAssoc($sql);
        if (! $res) {
            $this->console->writeLine("$username 不存在");
            return;
        }
        $em->getConnection()->update('user', array('role'=>'系統管理者'), array('username'=>$username));

        if ($password) {
            $newPassword = md5($password);
            $em->getConnection()->update('user',array('password'=>$newPassword),array('username'=>$username));
            $this->console->writeLine("$username 重設密碼為 $password");
        }

        $console->writeLine('建立完成!!', ColorInterface::GREEN);
        $console->writeLine('預設帳號 ' . $username . ', 密碼 ' . $password, ColorInterface::GREEN);

    }
}