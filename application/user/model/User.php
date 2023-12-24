<?php
namespace app\user\model;
use app\common\model\Base;
use think\captcha\Captcha;
use app\common\model\Jwt_base;
use think\Db;
use think\Model;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


class User extends Model{
    public $jwt;
    public function __construct($data = [])
    {
        parent::__construct($data);
        $this->jwt=new Jwt_base();
    }
    //用户登录
    public function user_login($phone,$password){
        $data=Db::table('user')->where('phone',$phone)->find();
        $state=Db::table('user')->where('phone',$phone)->value('state');
        //var_dump($data);
        if($state==0){
            echoJson(0,'账号已被管理员禁用，请联系管理员');
        }
        $password=md5($password);
        //echo $account."  ".$password;
        if($password==$data['password']){
            return $this->jwt->encode($data['id'],$data['roleId'],$phone);
        }else{
            echoJson(0,'密码错误');
        }
    }
    //用户注册
    public function user_enroll($phone,$password){
//        $redis = init_redis();
//        $temp = $redis->hGet($phone,'code');
//        if($temp != $code){
//            echoJson(0,"验证码错误");
//        }
        $data=Db::table('user')->where('phone',$phone)->select();
        if(!empty($data)){
            echoJson(0,'手机号已被注册');
        }
        $count=Db::table('user')->count()+1;
        $data=[
            'username'=>"user_".$count,
            'id'=>$count+1,
            'phone'=>$phone,
            'password'=>md5($password),
            'roleId'=>'2',
            'state'=>'1',
            'useful_time'=>date('Y-m-d H:i:s',time())
        ];
        $result=Db::table('user')->insert($data);
        if(!empty($result)){
            echoJson('1','注册成功');
        }
    }
    //验证验证码
    public function verify_captcha($code)
    {
        $captcha = new Captcha();
        if ($captcha->check($code)) {
            return false;
        } else {
            return true;
        }
    }
    //删除用户
    public function user_del($id){

    }


    //重置密码
    public function restpassword($phone,$newpassword){
        $data=[
            'password'=>md5($newpassword)
        ];
        Db::table('user')->where('phone',$phone)->update($data);
        echoJson(1,'密码更改成功，请返回登录');
    }

    //更新用户状态
    public function updateuser($id,$state){
        $data=[
            'state'=>$state
        ];
        Db::table('user')->where('id',$id)->update($data);
        echoJson(1,'状态更新成功');
    }
    //设置用户权限
    public function setuserpermissions($id,$roleId){

        $data=[
            'roleId'=>$roleId
        ];
        Db::table('user')->where('id',$id)->update($data);
        echoJson(1,'设置权限成功');
    }

    //删除用户
    public function deluser($id){
        Db::table('user')->where('id',$id)->delete();
        echoJson(1,'删除成功');
    }

    //管理员登录
    public function loginadmain($phone,$password){
        $data=Db::table('user')->where('phone',$phone)->find();
        $state=Db::table('user')->where('phone',$phone)->value('state');
        if($data['roleId']>1){
            echoJson(0,"该用户不是管理员");
        }
        //var_dump($data);
        if($state==0){
            echoJson(0,'账号已被管理员禁用，请联系超级管理员');
        }
        $password=md5($password);
        //echo $account."  ".$password;
        if($password==$data['password']){
            return $this->jwt->encode($data['id'],$data['roleId'],$phone);
        }else{
            echoJson(0,'密码错误');
        }
    }

    //查询指定用户
    public function read($username,$name,$phone,$roleld,$state,$email, $page, $count){
        $data = Db::table('user');
        $temp = Db::table('user');
        if (!empty($username)) {
            $temp = $data->where('username', 'like', '%'.$username.'%');
            $data = $data->where('username', 'like', '%'.$username.'%');
        }

        if (!empty($name)) {
            $data = $data->where('name', 'like', '%'.$name.'%');
            $temp = $data->where('name', 'like', '%'.$name.'%');
        }

        if (!empty($phone)) {
            $temp = $data->where('phone', 'like', '%'.$phone.'%');
            $data = $data->where('phone', 'like', '%'.$phone.'%');
        }

        if (!empty($roleld)) {
            $results = Db::table('role')->where('name', 'like', '%' . $roleld . '%')->select();
            foreach ($results as $res) {
                $data = $data->where('roleId', $res['id']);
                $temp = $data->where('roleId', $res['id']);
            }
        }

        if (!empty($state)) {
            $data = $data->where('state', $state);
            $temp = $data->where('state', $state);
        }

        if (!empty($email)) {
            $data = $data->where('email', 'like', '%'.$email.'%');
            $temp = $data->where('email', 'like', '%'.$email.'%');
        }

        $data = $data
            ->page($page,$count)
            ->select();
        $temp = $temp->count();
        echoJson(1 ,'查询成功', $data, $page, $temp);
    }

    //用户修改个人信息
    public function putdmessage()
    {

    }
}