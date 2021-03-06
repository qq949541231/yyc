<?php
namespace app\index\controller;

class Index
{
    private $version = 'v1.0';//后台版本
    private $functionType="entrance";//控制器类型
    private $updateTime="2019-7-5";//更新时间
    private $geminiLogo="./public/static/gemini.png";//logo 

    public function index()
    {
        return 'EDZero Comein';    
    }
    

    //组件依赖
    public function component()
    {
        return '';
    }

    //后台目录结构
    public function directory(){
        return '';  
    }
}
