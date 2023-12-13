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
        $phone = $_GET['phone'];
        $redis = init_redis();
        $temp = $redis->ttl($phone);
        if ($temp != -1 && $temp != -2){
            echoJson(0,'请不要重复调用');
        }
        $verification_code = str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
        $redis->hSet($phone,'code',$verification_code);
        $redis->expire($phone, 180);
        echoJson(1,'验证码', $verification_code);
    }
}