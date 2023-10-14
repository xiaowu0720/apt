<?php
namespace app\user\controller;


use app\common\model\Jwt_base;
use app\user\model\User;
use think\App;
use think\Controller;

class Deluser extends Controller{
    public $request;
    public $user;
    public $info;
    public $jwt;
    public function __construct(App $app = null)
    {
        parent::__construct($app);
        $this->info=$_REQUEST;
        $this->user=new User();
    }

    public function deluser(){
        $id=$this->info['id'];
        $this->user->deluser($id);
    }
}