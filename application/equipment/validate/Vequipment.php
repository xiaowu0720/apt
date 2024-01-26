<?php


namespace app\equipment\validate;


class Vequipment extends \think\Validate
{
    protected $rule = [
        'device_address' => 'require',
    ];

    protected $message = [
        'device_address' => 'The device address cannot be empty',
    ];
}