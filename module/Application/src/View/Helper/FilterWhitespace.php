<?php


namespace Application\View\Helper;


use Zend\View\Helper\AbstractHelper;

class FilterWhitespace extends AbstractHelper
{


    public function __invoke($str,$replaceString = '_')
    {
        $str = trim($str);
        return  preg_replace('/\s(?=)/', $replaceString , $str);
    }
} 