<?php
//重置密码

namespace app\user\controller;


use app\user\model\User;
use think\App;
use think\Controller;

class Forgetpassword extends Controller{
    public $info;
    public $user;
    public function __construct(App $app = null)
    {
        $this->info=$_POST;
        $this->user-new User();
        parent::__construct($app);
    }

    public function forgetpassword(){
        $phone=$this->info['phone'];
        $code=$this->info['code'];
        $newpassword=$this->info['newpassword'];
        $repeatnewpassword=$this->info['repeatnewpassword'];
        if($newpassword!==$repeatnewpassword){
            echoJson(0,'两次密码不一样');
        }
        $redis=init_redis();
        $verify=$redis->get($phone);
        if($verify!==$code){
            echoJson(0,'验证码错误');
        }
    }
}