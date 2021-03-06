<?php


namespace app\entrance\controller\JD\miniprogrem;


use app\entrance\base\BaseController;

use app\common\Exceptions\ApiExceptions;

use app\entrance\base\ApiErrDesc;

use app\entrance\model\Order;

use app\entrance\model\User;

use app\entrance\model\Random;

use app\entrance\model\Wechat;

use app\entrance\model\Ticket;

use think\Request;

use think\console\output\descriptor\Console;

use EasyWeChat\Factory;

use app\think\Exception;

/**
 * @title 小程序端接口
 * @description 接口说明
 * @header name:token type:varchar require:1 default:1 other:'' desc:数据表明
 */
class WxHome extends BaseController
{
    
    //微信小程序登录接口

    public function wx_login($request){

        $nickname = $request->param('nickname');

        $avatar = $request->param('avatar');

        $gender = $request->param('gender');

        $city = $request->param('city');

        $mobile = $request->param('mobile');

        $last_login_time = date('Y-m-d H:i:s',time());

        $code = $request->param('code');

        // 指定 API 调用返回结果的类型：array(default)/collection/object/raw/自定义类名

        $app = Factory::miniProgram(Wechat::login());

        $res = $app->auth->session($code);

        $openid = $res['openid'];

        $session_key = $res['session_key'];

        $count = User::where('openid',$openid)->count();

        if($count){

            $data = [

                //如果获取到最新的用户信息就做更新操作

                // 'nickname'=>$nickname,

                // 'avatar'=>$avatar,

                // 'gender'=>$gender,

                // 'city'=>$city,

                // 'mobile'=>$mobile,

                'last_login_time'=>$last_login_time,

            ];

            User::where('openid',$openid)->update($data);

            $message = User::where('openid',$openid)->find();

            return $this->jsonSuccessData($message);

        }

        else{

            \exception('登录失败，请先注册');

        }
  
    }





    
    //微信小程序注册接口
    
    public function wx_register($request){
    
        $nickname = $request->param('nickname');
    
        $avatar = $request->param('avatar');
    
        $gender = $request->param('gender');
    
        $city = $request->param('city');
    
        $mobile = $request->param('mobile');
    
        $code = $request->param('code');
    
        $create_time = date('Y-m-d H:i:s',time());
    
        // 指定 API 调用返回结果的类型：array(default)/collection/object/raw/自定义类名
    
        $app = Factory::miniProgram(Wechat::login());
    
        $res = $app->auth->session($code);
    
        $openid = $res['openid'];
    
        $session_key = $res['session_key'];

        // 获取数据库的值，判断
        
        $nickname1 = User::where('openid',$openid)->value('nickname');
        
        $avatar1 = User::where('openid',$openid)->value('avatar');
        
        $gender1 = User::where('openid',$openid)->value('gender');
        
        $city1 = User::where('openid',$openid)->value('city');
        
        $mobile1 = User::where('openid',$openid)->value('mobile');

        $empty = User::where('openid',$openid)->count();
        
        if(!$empty){
        
            if($gender == '1'){
        
                $gender = '男';
            }
        
            else if($gender == '2'){
        
                $gender = '女';
        
            }
        
            $data = [
        
                'nickname' => $nickname,
        
                'avatar'=>$avatar,
        
                'gender' => $gender,
        
                'city'=>$city,
        
                'openid'=>$openid,
        
                'mobile'=>$mobile,
        
                'create_time'=>$create_time,
        
            ];
        
            User::insert($data);
        
            $message = User::where('openid',$openid)->find();
        
            return $this->jsonSuccessData($message);
        
        }
        
        // 判断基本信息是否更新
        
        else if($nickname1 != $nickname || $avatar1 != $avatar || $gender1 != $gender
        
        || $city1 != $city || $mobile1 != $mobile
        
        ){
        
            $data = [
        
                'nickname' => $nickname,
        
                'avatar'=>$avatar,
        
                'gender' => $gender,
        
                'city'=>$city,
        
                'openid'=>$openid,
        
                'mobile'=>$mobile,
        
            ];
        
            User::where('openid',$openid)->update($data);
        
            $message = User::where('openid',$openid)->find();
        
            return $this->jsonSuccessData($message);
        
        }
        
        else{
        
            $message = User::where('openid',$openid)->find();
        
            return $this->jsonSuccessData($message);
        
        }
    }











    
    //订单生成


    
    public function order_create($request){

        $Random = new Random();
    
        $count = $request->param('count');
    
        $ticket_id = $request->param('ticket_id');
    
        $user_id = $request->param('user_id');
    
        $out_trade_no = Random::random(14);
    
        // $code = $Random->random(14);
    
        $price = Ticket::where('id',$ticket_id)->value('price');
    
        // $price = Order::alias('a')->join('yyc_ticket b','b.id = a.ticket_id');
    
        $total_price = $count*$price;
    
        $create_time = date('Y-m-d H:i:s',time());
    
        $pay_status = 0;
    
        $type = 1;
    
        $data = [
    
            'out_trade_no' => $out_trade_no,
    
            'count'=>$count,
    
            'price' => $price, 
    
            'total_price' => $total_price,
    
            'create_time' => $create_time,
    
            'pay_status' => $pay_status,
    
            'type' => $type,
    
            'ticket_id' => $ticket_id,
    
            'user_id' => $user_id,
    
            // 'code' => $code,
       
        ];

        
        Order::insert($data);
        
        $message = Order::where('out_trade_no',$out_trade_no)->find();
        
        return $this->jsonSuccessData($message);
    }



    
    //核销 返回
    
    public function use_status($request){
    
        $code = $request->param('code');
    
        $out_trade_no = $request->param('out_trade_no');
    
        $order = Order::where('out_trade_no',$out_trade_no);
    
        $use_time = date('Y-m-d H:i:s',time());

        if($order->value('use_status') == '1'){

            if($code == $order->value('code')){
    
                $data = ['use_status' => '2','use_time' => $use_time,'pay_status' => '2'];
        
                Order::where('code',$code)->update($data);
        
                return $this->jsonSuccessData('核销成功，请进入游泳池');
    
            }
            else{
        
                \exception('核销码不存在，你买假票，我已经报警了');
        
            }
            
        }

        else{

            \exception('核销码已核销过了，我已经报警了');

        }
    
    }




    
    //预支付
    
    public function order_pay($request){
    
        $out_trade_no = $request->param('out_trade_no');
    
        $total_price = Order::where('out_trade_no',$out_trade_no)->value('total_price') * 100;

        $user_id = Order::where('out_trade_no',$out_trade_no)->value('user_id');
        
        $openid = User::where('id',$user_id)->value('openid');
        
        $pay = Factory::payment(Wechat::pay());
        
        $result = $pay->order->unify([
        
            'body'             => '游泳池入门票',
        
            // 'detail'           => '游泳池入门票',
        
            'out_trade_no'     => $out_trade_no, // 这是自己ERP系统里的订单ID，不重复就行。
        
            'total_fee'        => 1, // 金额，这里的8888分人民币。单位只能是分。
        
            'notify_url'       => 'http://swm3.jiedx.club/entrance/JD/no/order_notify', // 支付结果通知网址，如果不设置则会使用配置里的默认地址
        
            'openid'           => $openid, // 这个不能少，少了要报错。
        
            'trade_type'       => 'JSAPI', // 微信公众号支付填JSAPI

        ]);

        // dump($result);
        
        if($result['return_code'] === "SUCCESS"){
        
            if($result['result_code'] === "SUCCESS"){

                //测试测试
        
                // $pay1 = $pay->jssdk->appConfig($result['prepay_id']);
        
                // $appid = $pay1['appid'];
        
                // $noncestr = $pay1['noncestr'];
        
                // $timestamp = $pay1['timestamp'];
        
                // $package = $result['prepay_id'];
        
                // $prepayid = $pay1['prepayid'];
        
                // $md5 = "appId='$appid'&nonceStr='$noncestr'&package=prepay_id='$prepayid'&signType=MD5&timeStamp='$timestamp'&key=69d8c12a862f965340067c68448cda4c";
        
                // $paySign = MD5($md5);
        
                // dump($paySign);
        
                // $message = [
        
                    //     'appId' => $appid,
        
                    //     'timeStamp' => time(),
        
                    //     'nonceStr' => Random::random(32),
        
                    //     'package' => 'prepay_id=' . $package,
        
                    //     'signType' => 'MD5',
        
                    //     'paySign' => $paySign,
        
                    // ];
        
                //传参给前端，挑起支付
        
                $message = array(

                    'appId' => $result['appid'],

                    'nonceStr' => Random::random(32),

                    'package' => 'prepay_id=' . $result['prepay_id'],

                    'signType' => 'MD5',

                    'timeStamp' => '' . time() . '',

                );

                //MD5加密paySign

                $message['paySign'] = Random::getSign($message);

                
            return $this->jsonSuccessData($message);
            // dump($message);   
            }
            
        }
        else{
            \exception('失败');
        }
    }





    //数据回调
    public function order_notify($request){
        
        $pay = Factory::payment(Wechat::pay());

        $response = $pay->handlePaidNotify(function($message, $fail){

            // 使用通知里的 "微信支付订单号" 或者 "商户订单号" 去自己的数据库找到订单

            $order = Order::where('out_trade_no',$message['out_trade_no']);    # 查询订单

            if(!$order->count()){

                $fail('Order not exist.');

            }

            if ($order->value('pay_status')  == '1') {

                return true;

            }

            if($message['return_code'] === 'SUCCESS'){

                if($message['result_code'] === 'SUCCESS'){

                    $effective_time = 24;

                    $pay_time = date('Y-m-d H:i:s',time());

                    $code = Random::random(14);

                    $end_time = date('Y-m-d H:i:s',(time() + $effective_time * 3600));

                    $pay_status = 1;

                    $transaction_id = $message['transaction_id'];

                    $data = [

                        'pay_status' => $pay_status,

                        'code'=>$code,

                        'transaction_id' => $transaction_id,

                        'end_time' => $end_time,

                        'pay_time' => $pay_time,

                        'effective_time' => $effective_time,

                    ];

                    $order->update($data);

                    return true;

                }

                else {

                    return $fail('支付失败');

                }

            }

            else {

                return $fail('通信失败，请稍后再通知我');

            }

            return true;

        });   

        return $response;

    }








    //退款
    public function order_refund($request){

        $pay = Factory::payment(Wechat::pay());
        
        $out_trade_no = $request->param('out_trade_no');
        
        $out_refund_no = Random::random(32);
        
        $totalFee = 1;
        
        $refundFee = 1;

        $refund_desc = '爱了';
        
        // 参数分别为：商户订单号、商户退款单号、订单金额、退款金额、其他参数
        
        $result = $pay->refund->byOutTradeNumber($out_trade_no,$out_refund_no,$totalFee,$refundFee,[
         
            'refund_desc' => $refund_desc,

            'notify_url' => 'http://swm3.jiedx.club/entrance/JD/no/order_refund_notify',
        
        ]);
        
        return $this->jsonSuccessData($result);
    }



    public function order_refund_notify($request){

        $pay = Factory::payment(Wechat::pay());

        $response = $pay->handleRefundedNotify(function ($message, $reqInfo, $fail) {

            $order = Order::where('out_trade_no',$reqInfo['out_trade_no']);    # 查询订单

            if(!$order->count() || $order->value('pay_status')  == '2'){

                return true;

            }

            if($message['return_code'] === 'SUCCESS'){

                if($reqInfo['refund_status'] === 'SUCCESS'){

                    $pay_status = 2;

                    $effective_time = 0;

                    $code = null;

                    $end_time = null;

                    $out_refund_no = $reqInfo['out_refund_no'];

                    $data = [

                        'pay_status' => $pay_status,
                       
                        'effective_time' => $effective_time,
                       
                        'code' => $code,
                       
                        'end_time' => $end_time,

                        'out_refund_no' => $out_refund_no,

                    ];

                    $order->update($data);

                    return true;

                }

                else {

                    return $fail('退款失败');

                }

            }

            else {

                return $fail('通信失败，请稍后再通知我');

            }
            
        });

        $response->send();

    }
    
}
