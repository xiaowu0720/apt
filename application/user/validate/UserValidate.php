<?php
namespace app\user\validate;

use think\Validate;

class UserValidate extends Validate{
    protected $rule = [
        'phone' => 'require',
        'password' => 'require'
    ];

    protected $message = [
        'phone.require' => '手机号码不能为空',
        'password.require' => '密码不能为空',
    ];
}