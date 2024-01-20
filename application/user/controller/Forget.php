<?php
//重置密码

namespace app\user\controller;


use think\App;
use think\Controller;
use think\Request;
use app\user\model\User;


class Forget extends Controller{
    public $user;
    public function __construct(App $app = null)
    {
        $this->user=new User();
        parent::__construct($app);
    }

    public function forget(Request $request){
        $phone=$request->param('phone');
//        $code=$this->info['code'];
        $newpassword=$request->param('newpassword');
        $repeatnewpassword=$request->param('repeatnewpassword');
        if (empty($phone)) {
            echoJson(0,'请输入手机号');
        }
        if($newpassword!==$repeatnewpassword){
            echoJson(0,'两次密码不一样');
        }
        $this->user->restpassword($phone,$newpassword);
//        $redis=init_redis();
//        $verify=$redis->get($phone);
//        if($verify!==$code){
//            echoJson(0,'验证码错误');
//        }
    }
}