<?php

namespace Base\View\Helper;

/**
 * 截字函式
 */
use Zend\View\Helper\AbstractHelper;

class WordWrap extends AbstractHelper
{


    public function __invoke($str, $width, $break)
    {
        $return = '';
        $br_width = mb_strlen($break, 'UTF-8');
        for($i = 0, $count = 0; $i < mb_strlen($str, 'UTF-8'); $i++, $count++)
        {
            if (mb_substr($str, $i, $br_width, 'UTF-8') == $break)
            {
                $count = 0;
                $return .= mb_substr($str, $i, $br_width, 'UTF-8');
                $i += $br_width - 1;
            }

            if ($count > $width)
            {
                $return .= $break;
                $count = 0;
            }

            $return .= mb_substr($str, $i, 1, 'UTF-8');
        }

        return $return;
    }


} 