<?php

namespace app\http\middleware;

use app\common\Response\ResponseJson;
use app\entrance\auth\JwtAuth;
use app\entrance\base\ApiErrDesc;
use think\Db;
use think\Request;

class WxJwtApi 
{   
    use ResponseJson;
    public function handle(Request $request, \Closure $next)
    {   
        $token= $request->param('token')?:$request->header('token');
        if($token){
            $jwtAuth =JwtAuth::getInstance();
            $jwtAuth->setToken($token);
            
            if($jwtAuth->validate()&&$jwtAuth->verify()){

                //逻辑操作
                /**
                 * 判断当前用户最新一条订单是否完结
                 * 
                 */
                $uid=$jwtAuth->getUid();
                $where='status<4 and userId='.$uid;
                $order=Db::table('yj_order')->where($where)->find();
                $message="";
                switch ($order['status'])
                {
                    case 1:
                    $message="您当前待付款订单未完成";
                    break;  
                    case 2:
                    $message="您当前待付款订单未完成";
                    break;
                    case 3:
                    $message="您当前待付款订单未完成";
                    break;
                    case 4:
                    $message="您当前待付款订单未完成";
                    break;
                    case 5:
                    $message="您当前待付款订单未完成";
                    break;
                    default:
                    $message="您当前待付款订单未完成";
                }
                return $next($request);
            }else{
               return  response($this->jsonData(ApiErrDesc::ErrMsg_TOKEN[0],ApiErrDesc::ErrMsg_TOKEN[1]));
            }
        }else{
            return response($this->jsonData(ApiErrDesc::ERR_PARAMS[0],ApiErrDesc::ERR_PARAMS[1]));
        }
    }


}
