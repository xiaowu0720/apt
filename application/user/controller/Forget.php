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
        $email = $request->param('email');
        $code=$request->param('code');
        $newpassword=$request->param('newpassword');
        $repeatnewpassword=$request->param('repeatnewpassword');
        $redis = init_redis();
        if ($code != $redis->get($email)) {
            echoJson(0, 'The verification code is incorrect');
        }
        if (empty($phone)) {
            echoJson(0,'Please enter your mobile phone number');
        }
        if($newpassword!==$repeatnewpassword){
            echoJson(0,'The password is not the same twice');
        }
        $this->user->restpassword($phone,$newpassword);
    }
}