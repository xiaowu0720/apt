<?php

namespace app\user\controller;

use think\App;
use think\Controller;
use think\Db;
use think\Request;

class User extends Controller
{
    public $user;
    public function __construct(App $app = null)
    {
        parent::__construct($app);
        $this->user = new \app\user\model\User();
    }

    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index(Request $request)
    {

        $username = $request->param('username',null);
        $name = $request->param('name', null);
        $phone = $request->param('phone', null);
        $roleId = $request->param('roleId', null);
        $state = $request->param('state',null);
        $email = $request->param('email',null);
        $page = $request->param('page', 1);
        $count = $request->param('count', 10);
        if (empty($page)) {
            $page = 1;
        }
        if (empty($count)) {
            $count = 10;
        }

        $this->user->read($username, $name, $phone, $roleId, $state, $email, $page, $count);
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        $phone=$request->param('phone');
        $password=$request->param('password');
//        $code = $this->info['code'];
        $data=[
            'phone'=>$phone,
            'password'=>$password
        ];
        $validate=new \app\user\validate\UserValidate();
        if(!$validate->check($data)){
            echoJson(0,$validate->getError());
        }
//        $this->user->user_enroll($phone,$password,$code);
        $this->user->user_enroll($phone,$password);
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        $data = Db::table('user')
            ->where('id',$id)
            ->select();
        echoJson(1,'Login successful',$data[0]);
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        $username = $request->param('username', null);
        $name = $request->param('name', null);
        $phone = $request->param('phone', null);
        $email = $request->param('email', null);
        $wecat = $request->param('wecat', null);
        $image = $request->param('image', null);
        $state = $request->param('date', null);
        $roleId = $request->param('roleId', null);
        $data = [];


        if (!empty($username)) {
            $data['username'] = $username;
        }

        if (!empty($name)) {
            $data['name'] = $name;
        }

        if (!empty($phone)) {
            $data['phone'] = $phone;
            $temp = Db::table('user')->where('phone',$phone)->select();
            if (!empty($temp) && $temp[0]['id'] != $id) {
                echoJson(0,'The mobile phone number has been registered');
            }
        }

        if (!empty($email)) {
            $data['email'] = $email;
        }

        if (!empty($wecat)) {
            $data['wecat'] = $wecat;
        }

        if (!empty($image)) {
            $data['image'] = $image;
        }

        if ($state === "0" || !empty($state)) {
            $data['state'] = $state;

        }
        if ($roleId === "0" || !empty($roleId)) {
            $data['roleId'] = $roleId;
        }
        Db::table('user')->where('id',$id)->update($data);
        echoJson(1,'The update was successful');
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        $this->user->deluser($id);
    }
}
