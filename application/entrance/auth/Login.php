<?php

namespace app\entrance\controller\dx\management;

use app\entrance\base\BaseController;
use app\entrance\base\Verification;


class Login extends BaseController
{
     /**
     * 用户登录 --账号密码登录
     */
    public function login($request)
    {
        //测试入口
        // echo "dx\management\login";
        // return '欢迎使用会员代销小程序!';
        //打印输出格式
        // var_dump($request); 

        //获取客户端参数
        $password=$request->param('password');//密码
        $account=$request->param('account');//账号
        // echo  "password->".$password."<br> account->".$account."<br>";

        //验证
        $validateHandle=Verification::getInstance();
        // $validateHandle=$this->validateHandle;
        // echo $validateHandle;
        $valudateData=[
            'password'=>$password,
            'account'=>$account
        ];
        // $validateHandle->processor($valudateData,"login");
        // $result=$validateHandle->processor($valudateData,"login");
        $result=$validateHandle->processor($valudateData,"login");
        return $result;
        // if(true !== $result)
        // {
        //     return $this->jsonData(ErrCode_VALIDATE,$result);
        // }
    }
}
