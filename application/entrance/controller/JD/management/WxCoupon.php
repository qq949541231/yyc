<?php


namespace app\entrance\controller\JD\management;


use app\entrance\base\BaseController;

use app\entrance\model\Coupon;

use app\entrance\model\Admin;

use think\DB;

use app\entrance\model\Random;
use Illuminate\Support\Facades\DB as FacadesDB;
use TencentCloud\Scf\V20180416\Models\Code;
use think\Request;


/**
 * @title 小程序端接口
 * @description 接口说明
 * @header name:token type:varchar require:1 default:1 other:'' desc:数据表明
 */
class WxCoupon extends BaseController

{

    public function coupon_select($request){
        $message = Coupon::select();
        return $this->jsonSuccessData($message);

    }

    public function coupon_random($request){

        // $coupon = Coupon::select();

        // return $coupon;

        // Random::getRand(1);
        
        // return mt_rand(1,80);

        // $arr = array();
        $count = Coupon::count();

        $message = Coupon::select();
        // foreach($message as $k => $v){
        //     $arr[$k] = $v;
        // }
        
        // return $count;

        $last = Coupon::order('id desc')->find();

        $max = ($last['id']+1) * 10;

        

        // for($i = 1; $i < $count; $i++){
        //     $arrid[$i] =  Coupon::where('id',$i)->value('id') * 10;
        //     $probability[$i] = $arrid[$i] + Coupon::where('id',$i)->value('probability');
        // }

        $random_num = mt_rand(1,8);

        // $i;

        // for($i = 1; $i < $count; $i++){
        //     if($random_num >= $arrid[$i] && $random_num <= $probability[$i]){
        //         // return $i;
        //         break;
        //     }
        // }

        return $random_num;

        // if($i == null){
        //     return $count;
        // }


        // return $max;

    }

}