<?php

namespace app\aliyun\controller;

use think\Controller;

class Getsignature extends Controller
{

    public function getOssSignature(\app\aliyun\model\Getsignture_model $oss){
        if ($this->request->isGet())
        {
            $road = 'test';
            echoJson(1,"The Alibaba Cloud signature is generated", $oss->getSignature($road));
        }
    }
}

