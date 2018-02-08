<?php
namespace Base\View\Helper;

use Interop\Container\ContainerInterface;
use Zend\View\Helper\AbstractHelper;


class Config extends AbstractHelper
{

    protected $config;

    public function __invoke()
    {
        return $this->config;
    }

    /**
     * School constructor.
     * @param ContainerInterface $container
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __construct(ContainerInterface $container)
    {
        $this->config = $container->get('config');
    }

}