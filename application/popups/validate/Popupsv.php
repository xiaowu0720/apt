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
        'start_date.require' => '开始日期不能为空',
        'end_date.require' => '截止日期不能为空',
        'content.require' => '内容不能为空',
        'title.require'=>'标题不能为空',
        'image.require'=>'请上传图片'
    ];
}