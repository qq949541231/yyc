<?php

namespace app\entrance\base;

use think\Controller;

class Verification extends Controller
{
    private static $instance = null;

    public function __construct()
    { }

    static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new Verification();
        }
        return self::$instance;
    }

    /*
    * 验证信息
    * $array=>['key1'=>'value1','key2'=>'value2']
    * $key=>对应validateApi的key值
    */
    public function getValidateApi($key)
    {
        $verification = self::$instance->validateApi;
        return $verification[$key];
    }

    /*
    * 控制器路径--methodApi
    * 'key'=>'namespace\验证器名'
    */
    private $validateApi = [
        'login' => 'app\entrance\validate\LoginValidate', //登陆验证
    ];
}
