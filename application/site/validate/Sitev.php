<?php


namespace app\site\validate;


use think\Validate;

class Sitev extends Validate
{
    protected $rule = [
        'name'      => 'require',
        'longitude' => 'require',
        'latitude'  => 'require',
        'address'   => 'require',
    ];

    protected $message = [
        'name.require'      => '站点名称不能为空',
        'longitude.require' => '经度不能为空',
        'latitude.require'  => '纬度不能为空',
        'address.require'   => '地址不能为空',
        'province.require'  => '省份不能为空',
        'county.require'    => '城市不能为空',
    ];
}