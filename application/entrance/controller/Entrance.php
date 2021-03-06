<?php

namespace app\entrance\controller;

use app\entrance\base\Method;

use \think\Request;

class Entrance
{
    public function __construct()
    {}
    
    //测试入口
    public function index(){
        return "entrance";
    }


    //单入口获取数据
    public function entrance(Request $request){
        //获取参数--TP请求助手
        // $request = request();
        // var_dump($request->header());
		
        //获取参数
        $request_data=$request->param(); //$request_data['xxx'] 获取数据格式

        //请求固定参数
        $version=$request_data['version'];//接口版本
        $method=$request_data['method'];//访问的方法---定义在method控制器中
        // echo $version;
        // echo $method;

        //获取访问方法API
        $methodType=Method::getInstance()->getMethod($method,$version);

        //分发到对应的api函数上
        $action=action($methodType,[$request]);
        return $action;
    } 
    

    //单入口获取数据 不需要验证
    public function noentrance(Request $request){
            $request_data=$request->param(); //$request_data['xxx'] 获取数据格式
    
            //请求固定参数
            $version=$request_data['version'];//接口版本
            $method=$request_data['method'];//访问的方法---定义在method控制器中
        
    
            //获取访问方法API
            $methodType=Method::getInstance()->getNoMethod($method,$version);
      
            //分发到对应的api函数上
            $action=action($methodType,[$request]);
            return $action;
    } 
}
