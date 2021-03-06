<?php
namespace app\entrance\base;

class AppConfig
{
    /**
     * $instance AppConfig实例
     */
    private static $instance= null;  

    private function __construct(){}

    static public function getInstance(){
        if(self::$instance==null)
        {
            self::$instance=new AppConfig();
        }
        return self::$instance;
    }

    /**
     * $ServerUrl 访问url 
     * 测试环境 http://dx.dreamgotrue.cn
     * 本地环境 http://gemini.localhost
     * */
    // private static $ServerUrl='http://gemini.localhost';
    private static $ServerUrl='https://yprw.sch100.cn';
    

    /**
     * 获取当前域名
     */
    public function getUrl(){
        return self::$ServerUrl;
    }

    /**
     * 资源保存根目录路径 
     */
    public $resourcePath = [
        'image' => "/public/static/resource/"
    ];

    /**
    * 获取api
    * $configType key值 
    */
    public function getConfig($configType){
        $appConfig=$this->configApi;//配置数组
        $currentConfig=$appConfig[$configType];//当前获取配置
        return $currentConfig;
    }
   /**
    * 获取api
    * $configWxType key值 
    */
    public function getWxConfig(){
        $appConfig=$this->configWXApi;//配置数组
        return $appConfig;
    }
    /**
    * 获取horapi
    * $configWxType key值 
    */
    public function getHorWxConfig(){
        $appConfig=$this->configHorWXApi;//配置数组
        return $appConfig;
    }
    /*
    * 配置路径--configApi
    */
    private $configApi=[

    ];

    /*
    * 配置路径--configWxApi
    */
    private $configWXApi=[

    ];

}
