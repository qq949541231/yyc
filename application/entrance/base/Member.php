<?php

namespace app\entrance\base;

use think\Controller;


/*会员基础信息模块*/

class Member extends BaseController
{
    private static $instance = null;
    public function __construct()
    { }
    static public function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new Member();
        }
        return self::$instance;
    }
    /**
     * 当前用户是否 达到购买上限
     */
    public function memberLimit($userId, $level, $limit)
    {
        $where = 'status=2 and userId=' . $userId . ' and level=' . $level;
        $count = $this->total('pay_member', $where);
        if ($limit == 0) {
            return false;
        }
        if ($count < $limit) { //未上限
            return true;
        }
        //已上限
        return false;
    }

    /**
     * 识别当前用户是否是会员
     */
    public function whethermember($userId)
    {
        $where = 'userId=' . $userId;
        $member = $this->find('member', $where);
        if (empty($member)) {
            return false;
        }
        if ($member['endTime'] > time()) {
            return true;
        }
        return false;
    }

    /**
     * 查找会员是否购买过会员
     */
    public function paymember($userId)
    {
        $where = 'userId=' . $userId;
        $member = $this->find('member', $where);
        if (empty($member)) {
            return false;
        } else {
            return true;
        }
    }
    /**
     * 查找用户最高等级的会员
     */
    public function Senior($userId)
    {
        $where = 'userId=' . $userId;
        $member = $this->find('member', $where);
        if (empty($member)) {
            return false;
        }
        return $member['level'];
    }
    /**
     * 查找用户购买表里 是否未到期的会员记录  
     */
    public function Unexpired($userId)
    {
        $where = 'userId=' . $userId;
        $member = $this->find('member', $where);
        if (empty($member)) { //未购买或者购买记录已添加deleteTime
            return false;
        }
        if ($member['endTime'] > time()) {
            return $member;
        }
        $dataArray = array(
            'deleteTime' => time()
        );
        $sql = 'id=' . $member['id'];
        $this->update('member', $sql, $dataArray);
        return false;
    }
    /**
     * 查找用户是否购买过体验会员
     */
    public function Experience($userId)
    { //需要视图关联
        $where = 'type=1 and userId=' . $userId;
        $member = $this->find('pay_member', $where);
        if (empty($member)) {
            return false;
        } else {
            return true;
        }
    }

    //创建置顶金额增送记录 管理员  20191212 
    public function createAdminTopPrice($userId,$openid,$toprice)
    {   
        $top_data = array(
            'userId' => $userId,
            // 'orderId' => $orderId,//购买会员标识
            'openid' => $openid,
            'tradeNo' =>"HT".$this->out_trade_no(),//让其创建的订单标识
            'change' => $toprice,
            'type' => 2,
            'createTime' => time(),
            'registime' => time(),
            'status' => 2,
            'source' => 3,
        );
        $this->insert('ceiling_amount', $top_data);
        return true;
    }
    /**创建置顶金额返还记录 */
    public function createAdminReturnTopPrice($userId,$openid,$toprice,$orderId)
    {   
        $top_data = array(
            'userId' => $userId, //用户表示
            'orderId' => $orderId,//购买会员标识
            'openid' => $openid,
            'tradeNo' =>"RT".$this->out_trade_no(),//让其创建的订单标识
            'change' => $toprice,
            'type' => 3,
            'createTime' => time(),
            'registime' => time(),
            'status' => 2,
            'source' => 4,
        );
        $this->insert('ceiling_amount', $top_data);
        return true;
    }

    //创建置顶金额增送记录 
    public function createTopPrice($userId,$openid, $orderId, $packageId,$tradeNo,$toprice="")
    {   
        //查找当前购买会员的设置信息
        $sql = 'id=' . $packageId;
        $package = $this->find('package', $sql);

        /** 识别当前赠送的置顶金额是否大于零  零则不送 */
        if ($package['topAmount'] <= 0) {
            return false;
        }
        //识别当前的购买是否已经插入记录
        $where = 'orderId=' . $orderId . ' and openid="' . $openid . '"';
        $top = $this->find('ceiling_amount', $where);
        if (!empty($top)) { //已经插入  终止操作
            return false;
        }
        /**todo */
        $topAmount=$toprice?:$$package['topAmount'];

        $top_data = array(
            'userId' => $userId,
            'orderId' => $orderId,//购买会员标识
            'openid' => $openid,
            'tradeNo' =>$tradeNo,//让其创建的订单标识
            'change' => $topAmount,
            'type' => 1,
            'createTime' => time(),
            'registime' => time(),
            'status' => 2,
            'source' => 1,
        );
        $this->insert('ceiling_amount', $top_data);
        return true;
    }

    //创建置顶金额 消费记录
    public function consumptionPrice($userId,$openid, $recedId, $change,$tradeNo)
    {
        //识别当前的购买是否已经插入记录

        if($change<=0){
            return false;
        }

        $where = 'recedId=' . $recedId . ' and openid="' . $openid . '"';
        $top = $this->find('ceiling_amount', $where);
        if (!empty($top)) { //已经插入  终止操作
            return false;
        }
        $top_data = array(
            'userId' => $userId,
            'recedId' => $recedId,//置顶消费订单标识
            'openid' => $openid,
            'tradeNo' =>$tradeNo,//让其创建的订单标识
            'change' => -$change,
            'type' => 0,
            'createTime' => time(),
            'registime' => time(),
            'status' => 2,
            'source' => 2,
        );
        $this->insert('ceiling_amount', $top_data);
        return true;
    }
}
