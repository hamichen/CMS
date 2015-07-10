<?php

namespace Base\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class BaseController extends AbstractActionController
{

    /**
     *
     * @var \Doctrine\Orm\EntityManager
     */
    protected $_em;


    public function getEntityManager()
    {
        if (!$this->_em)
            $this->_em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');

        return $this->_em;
    }


}

