<?php

namespace app\image\validate;

use think\Validate;

class ImageV extends Validate
{
    protected $rule = [
        'image'      => 'require',
    ];

    protected $message = [
        'image.require' => '图片地址不能为空',
    ];

}