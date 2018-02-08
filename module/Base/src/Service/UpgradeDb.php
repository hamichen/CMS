<?php
namespace Base\Service;

use Interop\Container\ContainerInterface;
use Zend\Console\ColorInterface;

class UpgradeDb
{
    protected $serviceLocator;
    protected $config;
    /**
     *
     * @var \Zend\Console\Adapter\AdapterInterface
     */
    protected $console;

    public function __construct(ContainerInterface $container)
    {
        $this->serviceLocator = $container;
        $config = getcwd() . DIRECTORY_SEPARATOR . 'config/upgrade.php';
        if (is_readable($config)) {
            if (null !== ($data = include ($config))) {
                $this->config = $data;
            }
        }
        $this->console = $this->serviceLocator->get('console');

    }

    /**
     * sfs3 資料庫
     * @return \Doctrine\DBAL\Connection
     */
    public function oldDb()
    {
        return \Doctrine\DBAL\DriverManager::getConnection($this->config['olddb']);
    }

    /**
     * 新資料庫
     * @return \Doctrine\DBAL\Connection
     */
    public function newDb()
    {

        return \Doctrine\DBAL\DriverManager::getConnection($this->config['newdb']);
    }

    /**
     *
     * @param array $arr
     *            資料陣列
     * @param string $table
     * @param string $message
     * @param integer $commit_step
     */
    public function insertToNewDb($arr, $table, $message, $commit_step = 100)
    {

        $this->console->writeLine('');
        $this->console->writeLine($message, ColorInterface::BLUE);
        $this->console->writeLine('=============================', ColorInterface::BLUE);
        $i = 0;
        $newDb = $this->newDb();
        foreach ($arr as $row) {
            $newDb->exec('SET autocommit=0');
            //  print_r($row);
            $newDb->insert($table, $row);
            if ($i++ % $commit_step == 0) {
                $this->console->writeText('.', ColorInterface::GRAY);
                $newDb->exec('commit');
            }
        }
        $newDb->exec('commit');
        $this->console->writeLine('');
        $message = '完成 ' . $message . ' ' . count($arr) . ' 筆';
        $this->serviceLocator->get('UpgradeLog')->info($message);
        $this->console->writeLine($message, ColorInterface::GREEN);
    }

}