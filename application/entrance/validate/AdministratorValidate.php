<?php

namespace app\entrance\validate;

use think\Validate;


class AdministratorValidate extends Validate
{
    protected $rule =   [
        'account'  => 'require|min:3|max:25|unique:Administrator',
        'password'   => 'require|min:6',
    ];
    protected $message  =   [
        'account.require' => '名称必须',
        'account.max'     => '名称最多不能超过25个字符',
        'account.min'     => '名称最少3个字符',
        'password.require'   => '密码必须',
        'password.min'   => '密码必须大于6位数',
    ];
    protected $scene=[
        'edit'=>['account'=>'require|unique:Administrator,account^id']
    ];
}
 