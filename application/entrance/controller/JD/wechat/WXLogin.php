<?php

namespace app\entrance\controller\JD\wechat;

use EasyWeChat\Factory;
use app\entrance\base\BaseController;

//微信公众平台登录
class WXLogin extends BaseController
{
    private  $config = [
        // 必要配置 
        'app_id' => 'wx6f62be280a77cb1e',
        'secret' => '7ebba15ea24393b54e6e8827162d0ef8',
        // 指定 API 调用返回结果的类型：array(default)/collection/object/raw/自定义类名
        'response_type' => 'array',
        'oauth' => [
            'scopes'   => ['snsapi_userinfo'],
            'callback' => '/oauth_callback',
        ],
    ];



    /**授权回调页*/
    public function oauth_callback()
    {
        $app = Factory::officialAccount($this->config);
        $oauth = $app->oauth;
        // 获取 OAuth 授权结果用户信息
        $user = $oauth->user()->toArray()['original'];
        $appdata=$this->saveUserInfo($user);

        $user['id']=$appdata['userInfo']['id'];
        $appdata['userInfo']=$user;
        return $appdata;
    }


    /** 用户信息检查储存*/
    private function saveUserInfo($user)
    {   
        $appdata=array(
            'userInfo'=>[],
        );
        //查找数据库里是否有用户信息
        $where='openid="'.$user['openid'].'"';
        $userInfo=$this->find('user',$where);
        if(empty($userInfo)){
            $userInfo=array(
                'openid'=>$user['openid'],
                'nickname'=>$user['nickname'],
                'avatar'=>$user['headimgurl'],
                'createTime'=>time(),
                'gender'=>$user['sex'],
                'city'=>$user['province'],
            );
            //添加用户
            $this->insert('user',$userInfo);
            $userInfo=$this->find('user',$where);
        }

        $appdata['userInfo']=$userInfo;
        return $appdata;
    }

    //JSSDK
    public function getJSSDK($url,$jsApiList){
        $app = Factory::officialAccount($this->config);
        if($url){
            $app->jssdk->setUrl($url);
        }
        $config=$app->jssdk->buildConfig($jsApiList);
        return $config;
    }

}
