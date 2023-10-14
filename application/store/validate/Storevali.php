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
        'addesc.require' => '商品简介不能为空',
        'phone.require' => '联系方式不能为空',
        'money.require'=>'商品金额不能为空',
        'adname.require'=>'商品名称不能为空',
    ];
}