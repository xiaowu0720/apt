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
//    public function get_captcha(){
//        $config = [
//            'length' =>4,           // 验证码字符长度
//            'fontSize' => 25,        // 字体大小
//            'useNoise' => true,      // 是否添加干扰线
//            'imageH' => 50,          // 验证码图片高度
//            'imageW' => 200,         // 验证码图片宽度
//            'fontttf' => '4.ttf',    // 字体文件
//        ];
//
//        $captcha = new \think\captcha\Captcha($config);
//
//        // 生成验证码图片
//
//        return $captcha->entry();
//        $email=$this->request->param('email');
//        $result=$this->validate([
//            'email'=>$email
//        ],
//            'app\user\validate\CodeValidate');
//        if(true!==$result){
//            return json([
//                'code'=>400,
//                'msg'=>$result,
//                'data'=>[]
//            ]);
//        }
//        $data=$this->user->captcha($email);
//        return $data;
//    }

    //短信验证测试
    public function getcode(){
        $phone=$this->info['phone'];
        $host = "https://dfsmsv2.market.alicloudapi.com";
        $path = "/data/send_sms_v2";
        $method = "POST";
        $appcode = "906f43b0fd314f45a2d74ba3efc5a347";
        $headers = array();
        array_push($headers, "Authorization:APPCODE " . $appcode);
        //根据API的要求，定义相对应的Content-Type
        array_push($headers, "Content-Type".":"."application/x-www-form-urlencoded; charset=UTF-8");
        $querys = "";
        $bodys = "content=code%3A1234&phone_number=".$phone."&template_id=TPL_0000";
        $url = $host . $path;

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_FAILONERROR, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, true);
        if (1 == strpos("$".$host, "https://"))
        {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }
        curl_setopt($curl, CURLOPT_POSTFIELDS, $bodys);
        var_dump(curl_exec($curl));
    }
}