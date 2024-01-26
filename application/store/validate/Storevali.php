<?php


namespace app\store\validate;


use think\Validate;

class Storevali extends Validate
{
    protected $rule = [
        'addesc'=>'require',
        'phone'=>'require',
        'money'=>'require',
        'adname'=>'require',
    ];

    protected $message = [
        'addesc.require' => 'The description cannot be empty',
        'phone.require' => 'Contact details cannot be left blank',
        'money.require'=>'The product amount cannot be empty',
        'adname.require'=>'The title cannot be empty',
    ];
}