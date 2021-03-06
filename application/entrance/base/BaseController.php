<?php

namespace app\entrance\base;
/*think类库*/

use think\Request;
use think\Controller;
use think\Db;

/*base基础模块*/
use app\entrance\base;
// use app\entrance\base\Verification;
// use app\entrance\base\Method;
// use app\entrance\base\BaseTool;

/* model模块 */


/*auth类模块*/
use app\entrance\auth\JwtAuth;

/*公共函数模块*/
use app\common\Response\ResponseJson;
use app\common\Err;
use think\db\Where;
use think\facade\Request as FacadeRequest;

class BaseController extends Controller
{
    use ResponseJson;

    //配置
    public $configHandle = null;
    //token验证
    public $jwtAuth = null;
    //工具函数
    public $baseTool = null;
    //token处理
    public $tokenHandle = null;
    //系统基本配置
    public $systemConfig = null;
    //会员查询
    public $memberConfig=null;
    public $uid = null;

    public function __construct()
    {
        $this->configHandle = AppConfig::getInstance();
        $this->jwtAuth = JwtAuth::getInstance();
        $this->baseTool = BaseTool::getInstance();
        $this->uid = $this->jwtAuth->getUid();
    }

    /**
     * 获取Token
     * $tokenId 包装的用户主键Id
     */
    public function getToken($tokenId)
    {
        //获取token
        $token = $this->jwtAuth
            ->setUid($tokenId)
            ->encode()
            ->getToken(); //包裝uid 跟用戶權限
        return $token;
    }

    /**
     * 保存资源
     * return $path
     * $path['URL'] 访问链接
     * $path['PATH'] 保存根目录 
     */
    public function saveResource($type)
    {
        //获取配置return $path;
        $url = $this->configHandle->getUrl(); //图片链接
        $root = $_SERVER['DOCUMENT_ROOT']; //项目根目录

        $path = array();
        $resourcePath = $this->configHandle->resourcePath;
        $imagePath = $resourcePath[$type];

        //拼接url和path
        $path['URL'] = $url . $imagePath.date('Ymd');
        $path['PATH'] = $root . $imagePath.date('Ymd');

        if (!file_exists($path['PATH'])) {
            // 用户文件夹不存在
            mkdir($path['PATH'], 0777, true);
        }
        return $path;
    }

    /**
     * 订单号生成
     * @return $orderSn
     */
    public function out_trade_no()
    {
        $yCode = ApiErrDesc::Out_Trade_No_Normal[1]; //正常

        $orderSn = $yCode[intval(date('Y')) - 2011]
            . strtoupper(dechex(date('m')))
            . date('d')
            . substr(time(), -5)
            . substr(microtime(), 2, 5)
            . sprintf('%02d', rand(0, 99));
        return $orderSn;
    }
    /**验证码 */
    public function range_yzm()
    {
        $arr3 = range(0, 9);
        $arr = array_merge($arr3);
        $keys = array_rand($arr, 4);
        $yzm = '';
        foreach ($keys as $k) {
            $yzm .= $arr[$k];
        }
        return $yzm;
    }
    /**
     * 生成码
     */
    public function generateCode($orderId, $i = null)
    {
        //订单id 用户 
        $ran = '';
        if (!empty($i)) {
            $ran = mt_rand(10, 99);
        }
        $string = base_convert($orderId, 10, 36);
        $len = strlen($string);
        for ($i = 0; $i < 6 - $len; $i++) {
            $t = mt_rand(0, 1);
            if ($t == 0) {
                $string = mt_rand(0, 9) . $string;
            } else {
                $string .= mt_rand(0, 9);
            }
        }
        return  $string . $ran;
    }

    /**
     * 查询一条字段
     * $database 数据库表明
     * $where 查询条件
     */
    public function find($database, $where = '')
    {
        $result = Db::name($database)
            ->where('deleteTime', NULL)
            ->where($where)
            ->find();
        return $result;
    }

    /**
     * 统计字段-and
     * $database 数据库表明
     * $where 查询条件
     * $field 需要查询的字段
     */
    public function total($database, $where = '', $field = '*')
    {
        $result = Db::name($database)
            ->where('deleteTime', 'NULL')
            ->where($where)
            ->count($field);
        return $result;
    }

    /**
     * 统计字段-or
     * $database 数据库表明
     * $where 查询条件
     * $field 需要查询的字段
     */
    public function totalOr($database, $where = '', $field = '*')
    {
        $result = Db::name($database)
            ->where('deleteTime', 'NULL')
            ->whereOr($where)
            ->count($field);
        return $result;
    }
    /**
     * 统计字段 
     * $database 数据库表明
     * $where 查询条件
     * $field 需要查询的字段
     */
    public function sum($database, $where = '', $field)
    {
        $result = Db::name($database)
            ->where('deleteTime', 'NULL')
            ->where($where)
            ->sum($field);
        return $result;
    }
    /**
     * 查询字段-and
     * $database 数据库表明
     * $where 查询条件
     * $field 需要查询的字段
     */
    public function select($database, $where = '', $field = '', $offset = 0, $length = 500, $order = null)
    {
        $result = Db::name($database)
            ->where('deleteTime', NULL)
            ->where($where)
            ->field($field)
            ->limit($offset, $length)
            ->order($order)
            ->select();
        return $result;
    }
    /**
     * 查询字段-and
     * $database 数据库表明
     * $where 查询条件
     * $field 需要查询的字段
     */
    public function selectno($database, $where = '')
    {
        $result = Db::query('select * from ' . $database . ' ' . $where);
        return $result;
    }
    /**
     * 查询字段-and
     * $database 数据库表明
     * $where 查询条件
     * $field 需要查询的字段
     */
    public function selectSQL($database, $where = '', $field)
    {
        $result = Db::query('select ' . $field . ' from ' . $database . ' ' . $where);
        return $result;
    }
    /**
     * 查询字段-or
     * $database 数据库表明
     * $where 查询条件
     * $field 需要查询的字段
     */
    public function selectOr($database, $where = '', $field = '', $offset = 0, $length = null, $order = null)
    {
        $result = Db::name($database)
            ->where('deleteTime', 'NULL')
            ->whereOr($where)
            ->field($field)
            ->limit($offset, $length)
            ->order($order)
            ->select();
        return $result;
    }

    public function selectorderRand($database, $where = '', $field = '', $offset = 0, $length = 500, $order = null)
    {
        $result = Db::name($database)
            ->where('deleteTime', NULL)
            ->where($where)
            ->field($field)
            ->limit($offset, $length)
            ->orderRand($order)
            ->select();
        return $result;
    }

    /**
     * selectOrderRaw
     */
    public function selectOrderRaw($database, $where = '', $field = '', $offset = 0, $length = null, $order = null)
    {
        $result = Db::name($database)
            ->where($where)
            ->field($field)
            ->limit($offset, $length)
            ->orderRaw($order)
            ->select();
        return $result;
    }

    /**
     * 新增一条数据
     * $database 数据库表明
     * $data 插入数据
     */
    public function insert($database, $data)
    {
        $result = Db::name($database)
            ->insert($data);
        // var_dump($result);
        if ($result)
            return $result;
        else {
            //To Do:错误异常捕捉
       
        }
    }
    /**
     * 新增多条数据
     * $database 数据库表明
     * $data 插入数据
     */
    public function insertAll($database, $data)
    {
        $result = Db::name($database)
            ->insertAll($data);
        // var_dump($result);
        if ($result)
            return true;
        else {
            //To Do:错误异常捕捉
            return $this->jsonData(ApiErrDesc::ErrMsg_INSERT[0], ApiErrDesc::ErrMsg_INSERT[1]);
        }
    }
    /**
     * 更新一条数据
     * $database 数据库表明
     * $where 更新条件
     * $data 插入数据
     */
    public function update($database, $where = '', $data)
    {
        $result = Db::name($database)
            ->where('deleteTime', 'NULL')
            ->where($where)
            ->data($data)
            ->update();
        // var_dump($result);
        if ($result)
            return true;
        else {
            //To Do:错误异常捕捉
            return $this->jsonData(ApiErrDesc::ErrMsg_UPDATE[0], ApiErrDesc::ErrMsg_UPDATE[1]);
        }
    }
}
