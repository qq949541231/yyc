<?php
namespace app\entrance\auth;

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;

use Lcobucci\JWT\ValidationData;
use Lcobucci\JWT\Parser;

/**
 * 单例  一次请求中所有出现使用JWT的地方都是一个用户
 */
class JwtAuth{
    
    /**
     * jwt token
     */
    private $token;
    /**
     * 单例模式 jwtAuth句柄
     */
    private static $instance;

    private $iss="gemini.sck.com"; //訪問地址
    private $aud="icon_pqp_p";
    private $uid; //用戶uid
    private $secrect="GEMINI_FOREVER";//密鑰


    /**
     * decode token 客户端传入的token
     */
    private $decodeToken;
    /**
     * 获取JWT句柄
     * @return JwtAuth
     */
    public static function getInstance(){
        if(is_null(self::$instance)){
            self::$instance=new self();
        }
        return self::$instance;
    }
    /**
     *  私有化构造函数
     */
    private function __construct()
    {

    }
    /**
     * 私有化clone函数
     */
    private function __clone(){

    }
    /**获取 token*/
    public function getToken(){
         return  (string)$this->token;
    }
    /**设置 tokne */
    public function setToken($token){
        $this->token=$token;
        return $this;
    }
    /**设置 uid */
    public function setUid($uid){
        $this->uid=$uid;
        return $this;
    }  
    public function getUid(){
        return  $this->uid;
    }
     
    /**
     * 编码JWT token
     */
    public function encode(){
        $time=time();
        $this->token=(new Builder())->setHeader('alg','HS256')
        ->setIssuer($this->iss)
        ->setAudience($this->aud)
        ->setIssuedAt($time)
        ->setExpiration($time+72000)
        ->set('uid',$this->uid)
        ->sign(new Sha256,$this->secrect)
        ->getToken();
        return $this;
    }

    public function decode(){
        if(!$this->decodeToken){
            $this->decodeToken=(new Parser())->parse((string)$this->token);
            $this->uid =$this->decodeToken->getClaim('uid');
    
        }
        return  $this->decodeToken;
    }

    /**verify */
    public function verify(){
        $result=$this->decode()->verify(new Sha256(),$this->secrect);
        return $result;
    }

    /**validate */
    public function validate(){
        $data= new ValidationData();
        $data->setIssuer($this->iss);
        $data->setAudience($this->aud);

        return $this->decode()->validate($data);
    }
}