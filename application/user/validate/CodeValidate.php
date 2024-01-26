<?php
namespace app\user\validate;

use think\Validate;

class CodeValidate extends Validate{
    protected $rule = [
        'email'    => 'require|email',
    ];

    protected $message = [
        'email.require' => 'The email address cannot be empty',
        'email.email' => 'The mailbox is malformed',
    ];
}