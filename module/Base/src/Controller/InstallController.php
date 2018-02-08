<?php


namespace Base\Controller;

use Zend\Console\ColorInterface;
use Interop\Container\ContainerInterface;
use Zend\Mvc\Controller\AbstractActionController;


class InstallController extends AbstractActionController
{
    protected $serviceManager;

    public function __construct(ContainerInterface $container)
    {
        $this->serviceManager = $container;
    }

    public function runAction()
    {

        /** @var  $em \Doctrine\Orm\EntityManager */
        $em = $this->serviceManager->get('doctrine.entitymanager.orm_default');
        $console = $this->serviceManager->get('console');
        $schemaTool = new \Doctrine\ORM\Tools\SchemaTool($em);


        $console->writeLine('建立資料表中, 請稍待!!', ColorInterface::GREEN);
        $classes = $em->getMetadataFactory()->getAllMetadata();

        if ($this->params()->fromRoute('re-create-database')) {
            $schemaTool->dropSchema($classes);
        }
        $schemaTool->createSchema($classes);

        // 安裝預設管理人員及選單
        $username = 'admin';
        $password = \Zend\Math\Rand::getString(8, null);
        $user = new \Base\Entity\User();
        $user->setUsername($username);
        $user->setPassword(md5($password));
        $user->setDisplayName('管理者');
        $user->setName('管理者');
        $user->setRole('系統管理者');
        $user->setIsActive(1);
        $em->persist($user);
        $em->flush();

        $menu = new \Base\Entity\Menu();
        $menu->setName('首頁');
        $menu->setUser($user);

        $params = [
            'max_records' => 10,
            'order_kind' => 'desc',
            'term' => ''
        ];
        $menu->setParams(serialize($params));
        $em->persist($menu);
        $em->flush();


        $console->writeLine('建立完成!!', ColorInterface::GREEN);
        $console->writeLine('預設帳號 ' . $username . ', 密碼 ' . $password, ColorInterface::GREEN);

    }
}