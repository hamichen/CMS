<?php
/**
 * Created by PhpStorm.
 * User: hami
 * Date: 2014/8/8
 * Time: 上午 9:31
 */

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Interop\Container\ContainerInterface;

class SelectedLayout extends AbstractHelper
{

    /** @var  ContainerInterface */
    private  $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }


    public function __invoke($subName='')
    {
        $layoutName = $this->container->get('config')['default_layout']['name'];
        if ($subName)
            $layoutName.= '-'.$subName;

        return 'layout/'.$layoutName.'.twig' ;
    }
} 