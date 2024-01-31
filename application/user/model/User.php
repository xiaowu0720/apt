<?php
namespace app\user\model;
use app\common\model\Base;
use think\captcha\Captcha;
use app\common\model\Jwt_base;
use think\Db;
use think\Model;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use think\View;


class User extends Model{
    public $jwt;
    public function __construct($data = [])
    {
        parent::__construct($data);
        $this->jwt=new Jwt_base();
    }
    //用户登录
    public function user_login($account,$password){
        $data=Db::table('user')->where('phone',$account)->find();
        if (empty($data)) {
            $data=Db::table('user')->where('email',$account)->find();
            $state=Db::table('user')->where('email',$account)->value('state');
            if (empty($data)) {
                echoJson(0,'不存在该用户');
            }
            if($state == 0){
                echoJson(0,'The account has been disabled by the administrator, please contact the administrator');
            }
        }else{
            $state=Db::table('user')->where('phone',$account)->value('state');
            //var_dump($data);
            if($state==0){
                echoJson(0,'The account has been disabled by the administrator, please contact the administrator');
            }
        }
        $password=md5($password);
        //echo $account."  ".$password;
        if($password==$data['password']){
            return $this->jwt->encode($data['id'],$data['roleId'],$account);
        }else{
            echoJson(0,'Wrong password');
        }
    }
    //用户注册
    public function user_enroll($phone,$password,$email){
//        $redis = init_redis();
//        $temp = $redis->hGet($phone,'code');
//        if($temp != $code){
//            echoJson(0,"验证码错误");
//        }
        $data=Db::table('user')->where('phone',$phone)->select();
        if(!empty($data)){
            echoJson(0,'The mobile phone number has been registered');
        }
        $data=Db::table('user')->where('email',$email)->select();
        if(!empty($data)){
            echoJson(0,'The email has been registered');
        }
        $count=Db::table('user')->count()+1;
        $data=[
            'username'=>"user_".$count,
            'id'=>$count+1,
            'phone'=>$phone,
            'password'=>md5($password),
            'roleId'=>'2',
            'state'=>'1',
            'email'=>$email,
            'useful_time'=>date('Y-m-d H:i:s',time())
        ];
        $result=Db::table('user')->insert($data);
        if(!empty($result)){
            echoJson(1,'Registration is successful');
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
        echoJson(1,'The password change is successful, please return to log in');
    }

    //更新用户状态
    public function updateuser($id,$state){
        $data=[
            'state'=>$state
        ];
        Db::table('user')->where('id',$id)->update($data);
        echoJson(1,'The status update was successful');
    }
    //设置用户权限
    public function setuserpermissions($id,$roleId){

        $data=[
            'roleId'=>$roleId
        ];
        Db::table('user')->where('id',$id)->update($data);
        echoJson(1,'The permission was set successfully');
    }

    //删除用户
    public function deluser($id){
        Db::table('user')->where('id',$id)->delete();
        echoJson(1,'The deletion is successful');
    }

    //管理员登录
    public function loginadmain($phone,$password){
        $data=Db::table('user')->where('phone',$phone)->find();
        $state=Db::table('user')->where('phone',$phone)->value('state');
        if($data['roleId']>1){
            echoJson(0,"The user is not an administrator");
        }
        //var_dump($data);
        if($state==0){
            echoJson(0,'The account has been disabled by the administrator, please contact the super administrator');
        }
        $password=md5($password);
        //echo $account."  ".$password;
        if($password==$data['password']){
            return $this->jwt->encode($data['id'],$data['roleId'],$phone);
        }else{
            echoJson(0,'Wrong password');
        }
    }

    //查询指定用户
    public function read($username,$name,$phone,$roleId,$state,$email, $page, $count){
        $data = Db::table('user');
        $temp = Db::table('user');
        if ($username) {
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
        if ($roleId === "0" || !empty($roleId)) {
            $data = $data->where('roleId', $roleId);
            $temp = $data->where('roleId', $roleId);
        }
        if ($state === "0" || !empty($state)) {
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
        echoJson(1 ,'The query succeeded', $data, $page, $temp);
    }

    //用户修改个人信息
    public function putdmessage()
    {

    }

    public function register($email,$code,$password){
        $user = $this->where('email',$email)->find();
        if($user) return reMsg(0,'The user already exists');

        // 设置验证码有效期60秒，过期清空
        if(time() > session('emailCodeStartTime') + 60){
            session('emailCode',null);
            return reMsg(0,'The verification code has expired');//reMsg是自己封装的方法，用于反馈数据库的操作结果
        }

        if($code != session('emailCode')) return reMsg(0,'The verification code has expired');

        $this->save([
            'email'     => $email,
            'password'  => md5($password)
        ]);
        // 当验证码成功使用，清空验证码
        session('emailCode',null);
        return reMsg(1,'Registration is successful');
    }
}