<?php
namespace app\user\validate;

use think\Validate;

class UserValidate extends Validate{
    protected $rule = [
        'account' => 'require',
        'password' => 'require'
    ];

    protected $message = [
        'account.require' => 'The account cannot be empty',
        'password.require' => 'The password cannot be empty',
    ];
}