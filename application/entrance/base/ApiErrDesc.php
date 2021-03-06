<?php

namespace app\entrance\base;



class ApiErrDesc extends \RuntimeException{

    /**API错误通用码  error_code<1000*/
    const SUCCESS=[0,'Success'];

    const UNKNOWN_ERR=[1,'未知错误'];
    const ERR_URl=[2,'访问接口不存在'];
    const ERR_PARAMS=[100,'参数错误'];
    const ERR_VALIDATE=[101,'验证错误'];
    /**error_code>1000 - 1100  用户登陆相关*/
    const ErrMsg_PASSWORD=[1000,'密码错误'];
    const ErrMsg_TOKEN=[1001,'登陆过期'];
    const ErrMsg_USER_NOFIND=[1002,'用户不存在'];
    const ErrMsg_USER_NOAUTH=[1003,'你没有访问权限'];
    const ErrMsg_USER_WXLOGIN=[1004,'小程序登录失败'];
    const ErrMsg_HORSEMANNO_WXLOGIN=[1005,'账号正在审核中，请稍等'];
    /**error-code>1100 -2000 数据处理*/
    const ErrMsg_DATA_NOFIND=[1100,'数据为空'];
    const ErrMsg_UPLOAD_IMAGE=[1101,'图片上传失败(请检查图片大小不超过5M,类型为jpg|png|jpeg|JPG)'];
    const ErrMsg_ERR_SAVE_NOFIND=[1102,'数据保存失败'];
    const ErrMsg_OPENID_NOFIND=[1103,'请检查用户是否登录获取openid'];
    const ErrMsg_WXPAY_NOFIND=[1104,'小程序登录失败'];
    const ErrMsg_PARAMERTER_NOFIND=[1105,'微信支付失败'];
    const ErrMsg_INSERT=[1106,'插入数据失败'];
    const ErrMsg_UPDATE=[1107,'更新数据失败'];
    const ErrMsg_VIEWSELECT=[1108,'获取视图失败'];
    const ErrMsg_ORDER_NOFIND=[1109,'订单不存在'];
    const ErrMsg_UPPER_LIMIT=[1110,'抱歉,您当前接单量已上限'];
    const ErrMsg_CLOSE_HORSEMAM=[1111,'你当前接单状态为:关闭状态,请开启后抢单'];
    const ErrMsg_ORDER_HORSEMAM=[1112,'当前订单正在进行中或已取消'];
    const ErrMsg_VIDEO=[1113,'摄像操作报错'];
    const ErrMsg_RIDER=[1114,'您当前已经'];
    const ErrMsg_BONDOFF=[1115,'优惠卷已被抢光'];
    const ErrMsg_NOFETOYZM=[1116,'您的验证码输入有误'];
    const ErrMsg_NOFIND_ZONE=[1117,'未找到当前区域'];
    const ErrMsg_OFFLINE_ZONE=[1118,'该劳务员已下线请选择其他劳务员'];
    const ErrMsg_UPLOAD_VIDEO=[1119,'视频上传失败(请检查图片大小不超过10M,类型为ogg|flv|avi|wmv|rmvb|mov|mp4)'];
    const ERR_BAG_MAMMER=[2007,'最低可提现金额为'];
     /**error-code>2000 -2100 金钱错误*/
    const ErrMsg_WALLET_NOFIND=[2001,'余额不足,还差￥'];
    const ErrMsg_WITHDRAW_ENOUGH=[2002,'抱歉您当前提现佣金不正确'];
    const ERR_RETREAT_MAMMER=[2003,'打款失败!请联系管理员'];
    /**error-code>2100 -3100 解密错误 */
    const ErrMsg_NOOPENDDOWN_FIND=[2100,'您还没有开柜取走钥匙'];
    const ErrMsg_NOTCLOSEDOWN_FIND=[2101,'请关好柜子，进入下一步操作'];
    const ErrMsg_THEBONDUSELOH=[2102,'对不起，您优惠卷已经使用过,请重新下单'];
    /**error-code>3100 -3150 订单类型*/
    const Out_Trade_No_Normal=[3100,array('AL', 'BL', 'CL', 'DL', 'EL', 'FL', 'GL', 'HL', 'IL', 'JL')];
    /** error-code>3150 -3200*/
    const ErrMsg_WARSH_NOFIND=[3150,'抱歉该洗车点不存在'];
}