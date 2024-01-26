<?php

namespace app\image\validate;

use think\Validate;

class ImageV extends Validate
{
    protected $rule = [
        'image'      => 'require',
    ];

    protected $message = [
        'image.require' => 'The image address cannot be empty',
    ];

}