<?php
namespace Base\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\Http\PhpEnvironment\Request;
use Zend\Mvc\MvcEvent;

class Params extends AbstractHelper
{

    protected $request;

    protected $event;

    public function __invoke()
    {
        return $this;
    }

    public function __construct(Request $request, MvcEvent $event)
    {
        $this->request = $request;
        $this->event = $event;
    }

    public function fromPost($params = null, $default = null)
    {
        if ($params === null) {
            return $this->request->getPost($params, $default)->toArray();
        }
        return $this->request->getPost($params, $default);
    }

    public function fromQuery($params = null, $default = null)
    {
        if ($params === null) {
            return $this->request->getQuery($params, $default)->toArray();
        }
        return $this->request->getQuery($params, $default);
    }

    public function fromRoute($params = null, $default = null)
    {
        if ($params === null) {
            return $this->event->getRouteMatch()->getParams();
        }
        return $this->event->getRouteMatch()->getParam($params, $default);
    }
}