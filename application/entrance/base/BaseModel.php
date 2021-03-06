<?php

namespace app\entrance\base;

use think\Db;
use think\Model;

/**
 * 新增模型前须知
 * 模型名对应数据表名,大小写需要注意,若数据库表名有大写,需要设置模型的table属性
 * 如果你的规则和上面的系统约定不符合,那么需要设置Model类的数据表名称属性,以确保能够找到对应的数据表.
 * database.php 设置
 * // 数据库表前缀
 * 'prefix'          => 'dx', 如 dx_user 
 */
class BaseModel extends Model
{
    // 模型初始化
    protected static function init()
    {
        //TODO:初始化内容
    }
}

?>

