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
        'name.require'      => 'The site name cannot be empty',
        'longitude.require' => 'Longitude cannot be empty',
        'latitude.require'  => 'The latitude cannot be empty',
        'address.require'   => 'The address cannot be empty',
        'province.require'  => 'Provinces cannot be empty',
        'county.require'    => 'Cities cannot be empty',
    ];
}