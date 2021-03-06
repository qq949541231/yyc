<?php

namespace app\http\middleware;

use app\common\Response\ResponseJson;
use app\entrance\auth\JwtAuth;
use app\entrance\base\ApiErrDesc;
use think\Request;

class JwtApi 
{   
    use ResponseJson;
    public function handle(Request $request, \Closure $next)
    {   
        $token= $request->param('token')?:$request->header('token');
        if($token){
            $jwtAuth =JwtAuth::getInstance();
            $jwtAuth->setToken($token);
            
            if($jwtAuth->validate()&&$jwtAuth->verify()){
                return $next($request);
            }else{
               return  response($this->jsonData(ApiErrDesc::ErrMsg_TOKEN[0],ApiErrDesc::ErrMsg_TOKEN[1]));
            }
        }else{
            return response($this->jsonData(ApiErrDesc::ERR_PARAMS[0],ApiErrDesc::ERR_PARAMS[1]));
        }
    }


}
