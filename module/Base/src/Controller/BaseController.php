<?php
namespace Base\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\Container;
use Zend\Http\Client;
use Interop\Container\ContainerInterface;

class BaseController extends AbstractActionController
{
    /** @var  \Zend\ServiceManager\ServiceManager */
    private $serviceManager;

    /**
     *
     * @var \Doctrine\Orm\EntityManager
     */
    protected $_em;

    /**
     * @var \Base\Entity\School
     */
    protected  $_school;

    /**
     * @var \Doctrine\ODM\MongoDB\DocumentManager
     */
    protected $_dm;

    /** @var \Zend\Authentication\AuthenticationService */
    protected $_authService;

    protected $_logger;

    protected $_config;

    /** @var  $_emCenter \Doctrine\ORM\EntityManager */
    protected $_emCenter = null;

    /*
     *  \Zend\Cache\Storage\StorageInterface
     */
    protected $_cache;

    protected $_session;

    public function __construct(ContainerInterface $container)
    {
        $this->serviceManager = $container;
        $this->_em = $container->get('doctrine.entitymanager.orm_default');
        $this->_dm = $container->get('doctrine.documentmanager.odm_default');
        $this->_config = $container->get('config');
        $this->_authService = $container->get('doctrine.authenticationservice.orm_default');
        $this->_logger = $container->get('MyLogger');


    }


    /**
     * @param $routeName
     * @param null $id
     * @return Client
     */
    public function getHttpClient($routeName,$id = null)
    {
        $restServerConfig = $this->serviceManager->get('config')['rest-manager-server'];

        $username = $restServerConfig['username'];
        $password = $restServerConfig['password'];
        $url = $restServerConfig['url'].'/'.$routeName;
        if ($id)
            $url = $url.'/'.$id;
        $client = new Client($url ,['maxredirects' => 0,'timeout'=> 30]);
        $adapter = new \Zend\Http\Client\Adapter\Curl();

        $adapter = $adapter->setCurlOption(CURLOPT_SSL_VERIFYPEER, false);
        $adapter = $adapter->setCurlOption(CURLOPT_SSL_VERIFYHOST, false);
        $client->setAdapter($adapter);

        //$client->setHeaders('WWW-Authenticate', 'Negotiate');
        $client->setAuth($username, $password, \Zend\Http\Client::AUTH_BASIC);
        $requestHeaders = $client->getRequest()->getHeaders();
        $headerString =  $headerString = 'Accept: application/json';
        $requestHeaders->addHeaderLine($headerString);

        return $client;
    }


    /**
     * 回傳單位名稱陣列
     * @return array
     */
    public function getBoeUnitArray()
    {
        $client = $this->getHttpClient('boe-unit');
        $response = $client->send();
        $jsonString = json_decode($response->getBody());
        $data = $jsonString->_embedded->boe_unit;
        $arr = [];
        foreach ($data as $val) {
            $arr[$val->id] = $val->name;
        }

        return $arr;

    }

    public function getAuthService() {
        if (!$this->_authService) {
            $this->_authService = $this->getServiceManager()->get('doctrine.authenticationservice.orm_default');
        }

        return $this->_authService;
    }

    public function getServiceManager()
    {
        return $this->serviceManager;
    }

    public function getEntityManager()
    {

        return $this->_em;
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function getCenterEntityManager()
    {
        if (!$this->_emCenter)
        $this->_emCenter = $this->serviceManager->get('doctrine.entitymanager.orm_center');

        return$this->_emCenter;
    }

    /**
     * @return \Doctrine\ODM\MongoDB\DocumentManager|mixed
     */
    public function getDocumentManager()
    {
        if (! $this->_dm){
            $this->_dm = $this->serviceManager->get('doctrine.documentmanager.odm_default');
            if ($school = $this->getSchool()){
                $this->_dm->getConfiguration()->setDefaultDB('community_'.$school->getSchoolNo());
            }

        }

        return $this->_dm;
    }


    /**
     * @return \Zend\Cache\Storage\StorageInterface
     */
    public function getCache()
    {
        if (! $this->_cache)
            $this->_cache =  $this->serviceManager->get('cacheApc');
        return $this->_cache;
    }


    /**
     * return \Base\Entity\School
     */

    public function getSchool()
    {

        if (! $this->_school){
            $session = new Container('school');

            if (!$session->school)
                return false;

            $this->_school = $session->school;
        }
        return $this->_school;
    }

    /**
     * @return \Zend\Session\Container
     */
    public function getSession()
    {
        if (!$school = $this->getSchool()) {
            return false;
        }

        $schoolId = $school->getId();
        if (!$this->_session) {
            $this->_session = new Container('school_'.$schoolId);

        }
        if (!$this->_session->guestSession)
            $this->_session->guestSession = $schoolId.'-'.time();
        return $this->_session;

    }
}