# CMS
文件管理系統
===========================
以 Zend Framework 2 架構的文件管理模組，以階層分類模式管理文件。

安裝方法
========

- 執行 composer 安裝必要套件

  php composer.phar update

- 更改 config/autoload/local.php.dist 為 config/autoload/local.php

  更改資料庫的設定,建立一個空白資料庫例 myweb

  ````
  return array(
      'doctrine' => array(
          'connection' => array(
              'orm_default' => array(
                  'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
                  'params' => array(
                      'dbname' => 'myweb',
                      'user' => 'root',
                      'password' => 'xxxx',
                      'host' => 'localhost',
                      'port' => 3306,
                      'charset' => 'utf8'
                  )
              )
          ),
          'authentication' => array(
              'orm_default' => array(
                  // should be the key you use to get doctrine's entity manager out of zf2's service locator
                  'objectManager' => 'doctrine.entitymanager.orm_default',
                  // fully qualified name of your user class
                  'identityClass' => 'Base\Entity\User',
                  // the identity property of your class
                  'identityProperty' => 'username',
                  // the password property of your class
                  'credentialProperty' => 'password',
                  // a callable function to hash the password with
                  'credentialCallable' => 'Base\Entity\User::hashPassword'
              )
          ),
          'migrations_configuration' => array(
              'orm_default' => array(
                  'directory' => 'data/migrations',
                  'namespace' => 'TravisDoctrineMigrations',
                  'table' => 'migrations',
              ),
          ),

      ),
      'phpSettings' => array(
              'display_startup_errors' => false,
              'display_errors' => true,
              'max_execution_time' => 60,
              'date.timezone' => 'Asia/Taipei',
              'mbstring.internal_encoding' => 'UTF-8',
          ),

          // 上傳檔案格式
          'upload' => array (
              'allow_type' => 'doc,docx,odt,ods,odp,xls,ppt,ppsx,txt,csv,zip,rar,7z,jpeg,jpg,png,gif,pdf,tif,mdi,mpeg,mp3,mp4',
              'image_type' => 'jpeg,jpg,png,gif'
          )
  );
  ````

- 執行安裝程式

  php public/index.php install

- 設定預設管理者密碼

  如要重設預設管理者密碼可執行

  php public/index.php set-admin



