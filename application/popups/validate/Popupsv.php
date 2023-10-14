<?php


namespace app\popups\validate;


use think\Validate;

class Popupsv extends Validate
{
    protected $rule = [
        'date' => 'require',
        'content' => 'require',
        'title'=>'require',
        'image'=>'require'
    ];

    protected $message = [
        'date.require' => '日期不能为空',
        'content.require' => '内容不能为空',
        'title.require'=>'标题不能为空',
        'image.require'=>'请上传图片'
    ];
}