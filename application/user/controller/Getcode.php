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
//    public function getcode() {
//        $phone = $_GET['phone'];
//
//        // 检查手机号是否为空
//        if (empty($phone)) {
//            echoJson(0, '手机号不能为空');
//        }
//
//        $redis = init_redis();
//        $key = "verification_code:$phone";
//
//        // 检查尝试次数是否达到上限
//        $attempts = $redis->incr("$key:attempts");
//        if ($attempts > 3) {
//            echoJson(0, '验证码更换次数已达上限');
//        }
//
//        // 检查是否可以生成新的验证码（一分钟内）
//        $lastChangeTime = $redis->get("$key:last_change_time");
//        $currentTime = time();
//
//        if ($lastChangeTime && ($currentTime - $lastChangeTime) < 60 && $attempts > 3) {
//            echoJson(0, '一分钟内只能更换三次验证码');
//        }
//
//        // 生成新的验证码
//        $verification_code = str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
//
//        // 更新验证码及相关信息
//        $redis->hSet($key, 'code', $verification_code);
//        $redis->expire($key, 60); // 设置过期时间为3分钟
//        $redis->set("$key:last_change_time", $currentTime);
//
//        echoJson(1, '验证码', $verification_code);
//    }
    public function getcode() {
        $email = $_GET['email'];
        $result = $this->validate([
            
        ]);
    }
}