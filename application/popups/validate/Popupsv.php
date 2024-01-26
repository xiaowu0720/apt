<?php


namespace app\popups\validate;


use think\Validate;

class Popupsv extends Validate
{
    protected $rule = [
        'start_date' => 'require',
        'end_date' => 'require',
        'content' => 'require',
        'title'=>'require',
        'image'=>'require'
    ];

    protected $message = [
        'start_date.require' => 'The start date cannot be empty',
        'end_date.require' => 'The deadline cannot be empty',
        'content.require' => 'The content cannot be empty',
        'title.require'=>'The title cannot be empty',
        'image.require'=>'Please upload an image'
    ];
}