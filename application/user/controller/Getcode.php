<?php
namespace app\user\controller;

use app\user\model\User;
use think\App;
use think\Controller;
use think\Request;
use think\captcha\Captcha;
use think\Session;


class Getcode extends Controller{
    public $request;
    public $user;
    public $info;
    public function __construct(App $app = null)
    {
        parent::__construct($app);
        $this->info=$_POST;
        $this->user=new User();
    }
    public function getcode(){
        $config =    [
            'fontSize'    =>    30,    // 验证码字体大小
            'length'      =>    4,     // 验证码位数
            'useNoise'    =>    true, // 关闭验证码杂点
        ];
        $captcha = new Captcha($config);
        return $captcha->entry();
    }
}