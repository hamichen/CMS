<?php
namespace Base\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\ServiceManager\ServiceLocatorAwareInterface;

class PageTitle extends AbstractHelper
{

	public function __invoke($title, $arr=[]) {
	    $str = '<header><h1 class="h3">';
	    if (isset($arr['icon']))
	        $str .= '<i class="'.$arr['icon'].'"></i> ';
	    $str .= $title.'</h1>
                </header>';

		return $str;

	}
}
