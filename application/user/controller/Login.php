<?php


namespace app\user\controller;

use think\Controller;
use app\user\model\User;
use think\Request;
use think\App;


class Login extends Controller {
    public $request;
    public $user;
    public $info;
    public function __construct(App $app = null)
    {
        parent::__construct($app);
        $this->user=new User();
        $this->info=$_POST;
    }
    public function user_login(Request $request){
        $phone=$request->post('phone');
        $password=$request->post('password');
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
//        $account=$this->request->param('account');
//        $password=$this->request->param('password');
//        $result=$this->validate(
//            [
//                'account'=>$account,
//                'password'=>$password
//            ],
//            'app\admin\validate\AdminValidate');
//
//        if(true!==$result){
//            return json([
//                'code'=>400,
//                'msg'=>$result,
//                'data'=>[]
//            ]);
//        }
//        $data=$this->user->user_login($account,$password);
//        //print_r($data);
//        return $data;