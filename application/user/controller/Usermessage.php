<?php
//
//
//namespace app\user\controller;
//
//
//use app\common\model\Jwt_base;
//use think\Controller;
//use think\Db;
//use think\Request;
//
//class Usermessage extends Controller
//{
//    public function usermessage(Request $request)
//    {
//        $id = ($request->data)['id'];
//        $data = Db::table('user')
//            ->where('id',$id)
//            ->field('id, username, name, phone, useful_time, email, wechat, image') // 排除 password 字段
//            ->select();
//        echoJson(1,'查询成功',$data[0]);
//    }
//}