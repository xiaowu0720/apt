<?php


namespace app\equipment\validate;


class Vequipment extends \think\Validate
{
    protected $rule = [
        'device_address' => 'require',
    ];

    protected $message = [
        'device_address' => '设备地址不能为空',
    ];
}