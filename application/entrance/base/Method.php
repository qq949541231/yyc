<?php
namespace app\entrance\base;

class Method
{
    private static $instance= null;

    private function __construct(){}

    static public function getInstance(){
        if(self::$instance==null)
        {
            self::$instance=new Method();
        }
        return self::$instance;
    }

    /*
    * 获取api
    * $methodType key值 
    * $version 接口版本
    * 搜索定位- methodApi
    */
    public function getMethod($methodType,$version){
        $Prefix=$this->namespacePrefix;//控制器前缀
        $apiVersion=$this->apiVersion[$version];//版本号
        $controller=$this->methodApi[$methodType];//控制器
        $api=$Prefix.$apiVersion.$controller;//api
        return $api;
    }

    /*
    * 主要给不需要验证的接口使用
    * $methodType key值 
    * $version 接口版本
    * 搜索定位- methodApi
    */
    public function getNoMethod($methodType,$version){
        $Prefix=$this->namespacePrefix;//控制器前缀
        $apiVersion=$this->apiVersion[$version];//版本号
        $controller=$this->methodNoApi[$methodType];//控制器
        $api=$Prefix.$apiVersion.$controller;//api
        return $api;
    }

    //路径前缀
    // private $namespacePrefix='app\entrance\controller';
    private $namespacePrefix='entrance';

    /*
    * 版本号--apiVersion
    */
    private $apiVersion=[
        'JD'=>'/JD',
    ];
    /**
     * 不需要token进入
     */
    private $methodNoApi=[
        // 登录路由
        'login'=>'/management/WxAdmin/login',

        //小程序端路由
       
        'wx_login'=>'/miniprogrem/WxHome/wx_login',
       
        'wx_register'=>'/miniprogrem/WxHome/wx_register',
       
        'wxlogin'=>'/miniprogrem/WxHome/wxlogin',
       
        'order_create'=>'/miniprogrem/WxHome/order_create',//订单生成

        'order_pay'=>'/miniprogrem/WxHome/order_pay',//微信支付
       
        'order_notify'=>'/miniprogrem/WxHome/order_notify',//支付数据回调
       
        'order_refund'=>'/miniprogrem/WxHome/order_refund',//退款
        
        'order_refund_notify'=>'/miniprogrem/WxHome/order_refund_notify',//退款数据回调
       
        'use_status'=>'/miniprogrem/WxHome/use_status',



       
        // 管理员路由
       
        'admin_select'=>'/management/WxAdmin/admin_select',
       
        'delete'=>'/management/WxAdmin/delete',
       
        'admin_create'=>'/management/WxAdmin/admin_create',
       
        'admin_find'=>'/management/WxAdmin/admin_find',
       
        'admin_update'=>'/management/WxAdmin/admin_update',


        
        // 用户路由
        
        'user_select'=>'/management/WxUser/user_select',
        
        'user_find'=>'/management/WxUser/user_find',
        
        'user_delete'=>'/management/WxUser/user_delete',
        
        'user_update'=>'/management/WxUser/user_update',
        
        'user_create'=>'/management/WxUser/user_create',


        // Banner路由
        
        'banner_select'=>'/management/WxBanner/banner_select',
        
        'banner_find'=>'/management/WxBanner/banner_find',
        
        'banner_delete'=>'/management/WxBanner/banner_delete',
        
        'banner_update'=>'/management/WxBanner/banner_update',
        
        'banner_create'=>'/management/WxBanner/banner_create',


        // 订单路由
        
        'order_select'=>'/management/WxOrder/order_select',
        
        'order_find'=>'/management/WxOrder/order_find',
        
        'order_delete'=>'/management/WxOrder/order_delete',
        
        'order_update'=>'/management/WxOrder/order_update',


        // 角色路由
        
        'role_select'=>'/management/WxRole/role_select',
        
        'role_find'=>'/management/WxRole/role_find',
        
        'role_delete'=>'/management/WxRole/role_delete',
        
        'role_update'=>'/management/WxRole/role_update',
        
        'role_create'=>'/management/WxRole/role_create',

        // 门票路由
        
        'ticket_select'=>'/management/WxTicket/ticket_select',
        
        'ticket_find'=>'/management/WxTicket/ticket_find',
        
        'ticket_delete'=>'/management/WxTicket/ticket_delete',
        
        'ticket_update'=>'/management/WxTicket/ticket_update',
        
        'ticket_create'=>'/management/WxTicket/ticket_create',


        //优惠券路由
        'coupon_select'=>'/management/WxCoupon/coupon_select',
        'coupon_random'=>'/management/WxCoupon/coupon_random',



    ];
    /*
    * 控制器路径--methodApi
    * 需要token进入
    */
    private $methodApi=[
      
    ];
}
