<?php

namespace app\entrance\validate;

use think\Validate;


class LoginValidate extends Validate
{
    protected $rule =   [
        'account' => 'require|min:3',    
        'password'   => 'require|min:6'
       
    ];
    protected $message  =   [
        'account.require'        => '用户名必须填写',
        'account.min'        => '用户名必须大于3位数',    
        'password.require'   => '密码必须填写',
        'password.min'   => '密码必须大于6位数'
    ];
}
