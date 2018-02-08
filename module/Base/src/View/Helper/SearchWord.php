<?php

namespace Base\View\Helper;

/**
 * 搜尋字串
 */
use Zend\View\Helper\AbstractHelper;

class SearchWord extends AbstractHelper
{


    public function __invoke($str,$res_str,$color="red")
    {
        if (empty($str))
            return '';
        $arr = preg_split ('[ +]', $res_str,10);
        foreach ($arr as $value) {
            $value = chop($value);
            if ($value) {
                $replace_text = "<span style='color:$color'><B>$value</B></span>";
                $str = str_replace($value,$replace_text,$str);
            }
        }
        return $str;
    }


} 