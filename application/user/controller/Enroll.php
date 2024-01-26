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
    public function user_enroll(){
        $phone=$this->info['phone'];
        $password=$this->info['password'];
        $email = $this->info['email'];
        $code = $this->info['code'];
        $data=[
            'phone'=>$phone,
            'password'=>$password,
            'email' => $email
        ];
        $redis = init_redis();
        if ($code != $redis->get($email)){
            echoJson(0, 'The verification code is incorrect');
        }
        $validate=new \app\user\validate\UserValidate();
        if(!$validate->check($data)){
            echoJson(0,$validate->getError());
        }
//        $this->user->user_enroll($phone,$password,$code);
        $this->user->user_enroll($phone,$password);
    }
}