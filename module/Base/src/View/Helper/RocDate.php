<?php


namespace Base\View\Helper;


use Zend\View\Helper\AbstractHelper;

class RocDate extends AbstractHelper
{

    /**
     * 將西元日期轉為民國年
     * @param string $day
     * @param string $split
     * @return string
     */
    public function __invoke($day = '', $split = '-', $dot = '-')
    {

        if ($day == '') //使用預設日期
            $day = date("Y-m-d");


        //把西元日期改為民國日期  $split為分隔符號
        $d = explode($split, $day);
        $d[0] = $d[0] - 1911;

        if ($dot == '民國')
            return $dot.$d[0] . '年' . $d[1] . '月' . $d[2] . '日';
        elseif ($dot == '中華民國')
            return $dot.$d[0] . '年' . $d[1] . '月' . $d[2] . '日';
        elseif ($dot == '年月日')
            return $d[0] . '年' . $d[1] . '月' . $d[2] . '日';
        elseif ($dot == '月日')
            return  $d[1] . '月' . $d[2] . '日';
        elseif ($dot == '月日週')
            return  $d[1] . '月' . $d[2] . '日';
         else
            return $d[0] . $dot . $d[1] . $dot . $d[2];

    }
} 