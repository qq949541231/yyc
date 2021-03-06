<?php

namespace app\entrance\model;

use think\Model;



class Wechat extends Model
{
    public static function login(){ 
        $config = [

            'app_id' => 'w***********f',//隐藏

            'secret' => '2*******1',//隐藏

        ];
        return $config;
   }

    public static function pay(){ 

        $config = [

            // 必要配置

            'app_id'             => 'w******f',     //隐藏

            'mch_id'             => '1*****7',      //隐藏

            'key'                => '6*****c',   // API 密钥    隐藏

            // 如需使用敏感接口（如退款、发送红包等）需要配置 API 证书路径(登录商户平台下载 API 证书)
            
            'cert_path'          => '../application/cert/apiclient_cert.pem', // XXX: 绝对路径！！！！
            
            'key_path'           => '../application/cert/apiclient_key.pem',      // XXX: 绝对路径！！！！

            'notify_url'         => 'http://swm3.jiedx.club/entrance/JD/no/order_notify',     // 你也可以在下单时单独设置来想覆盖它
        ];

        return $config;
    }

}
 