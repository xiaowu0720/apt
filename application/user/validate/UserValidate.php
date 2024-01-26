<?php
namespace app\user\validate;

use think\Validate;

class UserValidate extends Validate{
    protected $rule = [
        'phone' => 'require',
        'password' => 'require'
    ];

    protected $message = [
        'phone.require' => 'The mobile phone number cannot be empty',
        'password.require' => 'The password cannot be empty',
    ];
}