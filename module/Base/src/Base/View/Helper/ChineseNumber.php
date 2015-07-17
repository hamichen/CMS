<?php
/**
 * Created by PhpStorm.
 * User: hami
 * Date: 2014/2/25
 * Time: 下午 1:58
 */

namespace Base\View\Helper;


use Zend\View\Helper\AbstractHelper;

class ChineseNumber extends AbstractHelper
{

    const CHINESE_NUMBER_TYPE_1 = 1;
    const CHINESE_NUMBER_TYPE_2 = 2;
    const CHINESE_NUMBER_TYPE_3 = 3;
    const CHINESE_NUMBER_TYPE_4 = 4;
    const CHINESE_NUMBER_TYPE_MONEY = 5;


    public function __invoke($num, $type = self::CHINESE_NUMBER_TYPE_1)
    {
        switch ($type) {
            case  self::CHINESE_NUMBER_TYPE_1 :
                return $this->type1($num,1);
                break;
            case  self::CHINESE_NUMBER_TYPE_2 :
                return $this->type1($num,2);
                break;
            case  self::CHINESE_NUMBER_TYPE_3 :
                return $this->type2($num, 1);
                break;
            case  self::CHINESE_NUMBER_TYPE_4 :
                return $this->type2($num ,2);
                break;
            case  self::CHINESE_NUMBER_TYPE_MONEY :
                return $this->type2($num,3);
                break;
        }

    }

    protected function type1($num ,$type=1)
    {
        if ($type == 1)
            $numc_arr = array(
                "Ｏ", "一", "二", "三", "四", "五", "六", "七", "八", "九"
            );
        else
            $numc_arr = array(
                "零", "壹", "貳", "參", "肆", "伍", "陸", "柒", "捌", "玖"
            );

        $str = '';
        $len = strlen($num);
        for($i = 0; $i < $len; $i++)
            $str.= $numc_arr[substr($num,$i,1)];
        return $str;

    }



    protected function type2($num, $type=1)
    {
        if ($type == 1) {
            $numc_arr = array(
                "Ｏ", "一", "二", "三", "四", "五", "六", "七", "八", "九"
            );
            $unic_arr = array("", "十", "百", "千");

            $unic1_arr = array("", "萬", "億", "兆", "京");
        }
        else if ($type == 2){
            $numc_arr = array(
                "零", "壹", "貳", "參", "肆", "伍", "陸", "柒", "捌", "玖"
            );
            $unic_arr = array("", "拾", "佰", "仟");

            $unic1_arr = array("", "萬", "億", "兆", "京");
        }
        else if ($type == 3) {
            $numc_arr = array(
                "零", "壹", "貳", "參", "肆", "伍", "陸", "柒", "捌", "玖"
            );
            $unic_arr = array("", "拾", "佰", "仟");

            $unic1_arr = array("元整", "萬", "億", "兆", "京");
        }

        $i = str_replace(',', '', $num); #取代逗號
        $c0 = 0;
        $str = array();
        do {
            $aa = 0;
            $c1 = 0;
            $s = "";
            #取最右邊四位數跑迴圈,不足四位就全取
            $lan = (strlen($i) >= 4) ? 4 : strlen($i);
            $j = substr($i, -$lan);
            while ($j > 0) {
                $k = $j % 10; #取餘數
                if ($k > 0) {
                    $aa = 1;
                    $s = $numc_arr[$k] . $unic_arr[$c1] . $s;
                } elseif ($k == 0) {
                    if ($aa == 1) $s = "0" . $s;
                }
                $j = intval($j / 10); #只取整數(商)
                $c1 += 1;
            }
            #轉成中文後丟入陣列,全部為零不加單位
            $str[$c0] = ($s == '') ? '' : $s . $unic1_arr[$c0];
            #計算剩餘字串長度
            $count_len = strlen($i) - 4;
            $i = ($count_len > 0) ? substr($i, 0, $count_len) : '';

            $c0 += 1;
        } while ($i != '');
        $string = '';
        #組合陣列
        foreach ($str as $v) $string .= array_pop($str);

        #取代重複0->零
        $string = preg_replace('/0+/', '零', $string);

        return $string;

    }

} 