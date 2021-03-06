<?php

namespace app\entrance\model;

use think\Model;

use app\entrance\model\Wechat;



class Random extends Model
{
    public static function random($num){ 
        $arr = array_merge(range(0,9),range('A','Z'),range('a','z'));
        $str = '';
        $arr_len = count($arr);
        for($i = 0;$i < $num;$i++){
            $rand = mt_rand(0,$arr_len-1);
            $str.=$arr[$rand];
        }
        return $str;
   }

    public static function getSign($Obj)
    {
        // foreach ($Obj as $k => $v) {
        //     $Parameters[$k] = $v;
        // }
        //签名步骤一：按字典序排序参数
        ksort($Obj);
        $String =  self::formatBizQueryParaMap($Obj, false);
        //签名步骤二：在string后加入KEY
        $String = $String . "&key=" . Wechat::pay()['key'];
        //签名步骤三：MD5加密
        $String = md5($String);
        //签名步骤四：所有字符转为大写
        $result_ = strtoupper($String);
        return $result_;
    }

    /** 格式化参数，签名过程需要使用 */
    public static function formatBizQueryParaMap($paraMap, $urlencode)
    {

        $buff = "";
        ksort($paraMap);
        foreach ($paraMap as $k => $v) {
            if ($urlencode) {
                $v = urlencode($v);
            }
            $buff .= $k . "=" . $v . "&";
        }
        $reqPar = '';

        if (strlen($buff) > 0) {
            $reqPar = substr($buff, 0, strlen($buff) - 1);
        }

        return $reqPar;
    }

    //优惠券抽奖 概率
    function getRand($proArr) {
	    $data = '';
	    $proSum = array_sum($proArr); //概率数组的总概率精度 

	    foreach ($proArr as $k => $v) { //概率数组循环
	        $randNum = mt_rand(1, $proSum);
	        if ($randNum <= $v) {
	            $data = $k;
	            break;
	        } else {
	            $proSum -= $v;
	        }
	    }
	    unset($proArr);
	    return $data;
	}
}
 