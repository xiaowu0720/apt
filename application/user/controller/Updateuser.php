<?php
namespace app\user\controller;

use app\common\model\Jwt_base;
use app\user\model\User;
use think\App;
use think\Controller;
use think\facade\Request;

class Updateuser extends Controller{
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
    public function updateuser(){
        $id=$this->info['id'];
        $state=$this->info['state'];
        $this->user->updateuser($id,$state);
    }
}
