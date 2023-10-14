<?php
namespace app\user\controller;


use think\App;
use think\Controller;
use think\Request;
use app\user\model\User;

class Enroll extends Controller{
    public $request;
    public $user;
    public $info;
    public function __construct(App $app = null)
    {
        parent::__construct($app);
        $this->info=$_POST;
        $this->user=new User();
    }

    //用户注册
    //暂时没有设置验证码
    public function user_enroll(){
        $phone=$this->info['phone'];
        $password=$this->info['password'];
        $data=[
            'phone'=>$phone,
            'password'=>$password
        ];
        $validate=new \app\user\validate\UserValidate();
        if(!$validate->check($data)){
            echoJson(0,$validate->getError());
        }
        $this->user->user_enroll($phone,$password);
    }
}