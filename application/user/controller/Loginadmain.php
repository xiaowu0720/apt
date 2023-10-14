<?php
namespace app\user\controller;


use app\common\model\Jwt_base;
use app\user\model\User;
use think\App;
use think\Controller;
use think\facade\Request;

class Loginadmain extends Controller{
    public $request;
    public $user;
    public $info;
    public $jwt;
    public function __construct(App $app = null)
    {
        parent::__construct($app);
        $this->jwt=new Jwt_base();
        $this->request = Request::instance();
        $this->user=new User();
        $this->info=$_POST;
    }
    public function loginadmain(){
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
        if($data=$this->user->user_login($phone,$password)){
            echoJson(1,'登录成功',$data);
        }
    }
}