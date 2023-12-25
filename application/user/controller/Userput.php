<?php
namespace app\user\controller;


use think\Controller;
use think\Db;
use think\Request;

class Userput extends Controller
{
    public function Userput(Request $request) {
        $id = ($request->data)['id'];
        $username = $request->param('username', null);
        $name = $request->param('name', null);
        $phone = $request->param('phone', null);
        $email = $request->param('email', null);
        $wecat = $request->param('wecat', null);
        $image = $request->param('image', null);
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
            if (!empty($phone)) {
                echoJson(0,'该手机号已被注册');
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
        Db::table('user')->where('id',$id)->update($data);
        echoJson(1,'更新成功');
    }
}