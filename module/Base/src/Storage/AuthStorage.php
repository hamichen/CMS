<?php

namespace Base\Storage;
use Zend\Authentication\Storage;

class AuthStorage extends Storage\Session
{
    public function __construct()
    {
        parent::__construct('app_client');

        $sessionConfigOptions = [
            'use_cookies'     => true,
            'cookie_httponly' => true,
            'gc_maxlifetime'  => 1800,
            'cookie_lifetime' => 1800,
        ];
        $this->getSessionManager()->getConfig()
            ->setOptions($sessionConfigOptions);
    }

    public function rememberMe($time)
    {
        $this->getSessionManager()->rememberMe($time);
    }

    public function clear()
    {
        $this->getSessionManager()->forgetMe();
        parent::clear();
    }

    public function getSessionManager()
    {
        return $this->session->getManager();
    }
}