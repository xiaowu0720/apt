<?php
namespace app\user\controller;


use app\common\model\Jwt_base;
use app\user\model\User;
use think\App;
use think\Controller;
use think\facade\Request;
//设置用户权限
class Setuserpermissions extends Controller{
    public $request;
    public $user;
    public $info;
    public $jwt;
    public function __construct(App $app = null)
    {
        parent::__construct($app);
        $this->request = Request::instance();
        $this->user=new User();
        $this->info=$_REQUEST;
    }

    public function setuserpermissions(){
        $id=$this->info['id'];
        $roleId=$this->info['roleId'];
        $this->user->setuserpermissions($id,$roleId);
    }
}