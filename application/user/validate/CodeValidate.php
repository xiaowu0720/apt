<?php
namespace app\user\validate;

use think\Validate;

class CodeValidate extends Validate{
    protected $rule = [
        'email'    => 'require|email',
    ];

    protected $message = [
        'email.require' => '邮箱地址不能为空',
        'email.email' => '邮箱格式错误',
    ];
}