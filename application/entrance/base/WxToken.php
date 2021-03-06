<?php

namespace app\entrance\base;

use app\entrance\base\AppConfig;
use app\entrance\base\BaseTool;

/**
 * 获取小程序调用唯一凭证 accessToken
 */
class WxToken
{
    /**
     * $instance WxToken实例
     */
    private static $instance = null;

    /**
     * $wxAccessToken 微信小程序 accessToken
     */
    private static $wxAccessToken = null;

    /**
     * $valid token获取时间
     */
    private static $valid = null;

    /**
     * $expires token有效时间 单位秒数
     */
    private static $expires = null;



    private function __construct()
    { }

    /**
     * 单例模式
     */
    static public function getInstancce()
    {
        if (self::$instance == null) {
            self::$instance = new WxToken();
            //第一个静态实例生成后,赋予初始化的accessToken等变量数据
            self::$instance->getWxToken();
        }
        return self::$instance;
    }

    /**
     * 请求获取accsstoken
     */
    public function tokenRequest()
    {
        $instance = self::getInstancce();
        // 获取配置
        $apiConfig = AppConfig::getInstance();
        $appid = $apiConfig->getConfig('APPID');
        $secret = $apiConfig->getConfig('SECRET');
        // self::$instance = new wxAccessToken;

        $weixin = file_get_contents("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . $appid . "&secret=" . $secret);
        $jsonData = json_decode($weixin, true);

        $instance::$wxAccessToken = $jsonData['access_token']; //更新当前token
        $instance::$expires = $jsonData['expires_in']; //更新token有效时间
        $instance::$valid = time() + $instance::$expires; //更新token到期时间
    }

    /**
     * 获取Token
     * return accessToken 返回Token
     */
    public function getWxToken()
    {
        $instance = self::getInstancce();
        if ($instance::$wxAccessToken == null) {
            // echo 'create!';
            $instance->tokenRequest();
        } else {
            //检测token是否过期
            if (time() > $instance::$valid) {
                // echo 'get new!';
                //已过期-重新请求token
                $instance->tokenRequest();
            }
        }
        return $instance::$wxAccessToken;
    }

    /**
     * 二维码保存路径
     */
    private $imagePath = "/public/static/shareImages";

    /**
     * 获取分享二维码-- 二维码生成接口B
     * $userId(string)      用户表主键
     * $page(string)        必须是已经发布的小程序存在的页面（否则报错），例如 pages/index/index, 根路径前不要填加 /,不能携带参数（参数请放在scene字段里），如果不填写这个字段，默认跳主页面
     * $width(number)       二维码的宽度，单位 px，最小 280px，最大 1280px
     * $auto_color(boolean) 自动配置线条颜色，如果颜色依然是黑色，则说明不建议配置主色调，默认 false
     * $line_color(Object)  auto_color 为 false 时生效，使用 rgb 设置颜色 例如 {"r":"xxx","g":"xxx","b":"xxx"} 十进制表示
     * $is_hyaline(boolean) 是否需要透明底色，为 true 时，生成透明底色的小程序
     * return imageUrl 二维码url
     */
    public function getShareCode($userId, $page = '', $width = 430, $auto_color = false, $line_color = '', $is_hyaline = false)
    {
        $instance = self::getInstancce();
        // 获取配置
        $apiConfig = AppConfig::getInstance();

        $imageName = '/shared' . $userId . '.jpg'; //图片名
        $url = $apiConfig->getUrl(); //图片url前缀
        
        $root = $_SERVER['DOCUMENT_ROOT']; //项目根目录
        $image_savePath = $root . $this->imagePath; //二维码保存目录


        //最大32个可见字符，只支持数字，大小写英文以及部分特殊字符：!#$&'()*+,/:;=?@-._~，其它字符请自行编码为合法字符（因不支持%，中文无法使用 urlencode 处理，请使用其他编码方式）
        $scene = 'inviteId=' . $userId.'&type=share';

        //二维码参数
        $code_data = array(
            'scene' => $scene,
            'page' => $page,
            // 'width' => $width,
            // 'auto' => $auto_color,
            // 'line_color'=>$line_color,
            // 'is_hyaline' => $is_hyaline
        );

        //二维码请求
        $minicode_post = array(
            'http' => array(
                'method' => 'POST',
                'header' => "content-type:application/json",
                'content' => json_encode($code_data)
            )
        );

        $context = stream_context_create($minicode_post);
        $post_url = "https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=" . $instance::$wxAccessToken;
        //echo $post_url.'<br>';
        $mini_code_pic = file_get_contents($post_url, false, $context); //返回二进制图片

        //保存二维码图片
        $ImageUrl = BaseTool::getInstance()->SaveImage($imageName, $image_savePath, $url,$imageName, 'Binary', $mini_code_pic);
        return $ImageUrl;
    }

}
