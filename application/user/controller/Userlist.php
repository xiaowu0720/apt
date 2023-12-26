<?php
//namespace app\user\controller;
//
//use app\common\model\Jwt_base;
//use app\user\model\User;
//use think\App;
//use think\Controller;
//use think\Db;
//use think\Request;
//
////用户列表
//class Userlist extends Controller{
//    public $request;
//    public $user;
//    public $info;
//    public $jwt;
//    public function __construct(App $app = null)
//    {
//        parent::__construct($app);
//        $this->jwt=new Jwt_base();
//        $this->user=new User();
//        $this->info=$_REQUEST;
//    }
//    public function userlist(Request $request)
//    {
//        $page = $request->param('page', 1);
//        $count = $request->param('count', 10);
//        if (empty($page)) {
//            $page = 1;
//        }
//
//        if (empty($count)) {
//            $count = 10;
//        }
//
//        $data = Db::table('user')
//            ->page($page,$count)
//            ->select();
//        $count = Db::table('user')->count();
//        echoJson(1,"查询成功",$data,$page,$count);
//    }
//
//    public function read(Request $request)
//    {
//        $username = $request->param('username',null);
//        $name = $request->param('name', null);
//        $phone = $request->param('phone', null);
//        $roleld = $request->param('roleld', null);
//        $state = $request->param('state',null);
//        $email = $request->param('email',null);
//        $page = $request->param('page', 1);
//        $count = $request->param('count', 10);
//        if (empty($page)) {
//            $page = 1;
//        }
//
//        if (empty($count)) {
//            $count = 10;
//        }
//
//        $this->user->read($username, $name, $phone, $roleld, $state, $email, $page, $count);
//    }
//}