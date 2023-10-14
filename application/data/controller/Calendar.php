<?php
namespace app\data\controller;


use think\Controller;
use think\Db;
use think\Request;

class Calendar extends Controller
{
    public function data(Request $request)
    {
        $site = $request->param('site');
        $date = $request->param('date');

        $data = Db::table('siteday')
            ->where('date','like',$date.'%')
            ->where('site',$site)
            ->field('date, aqi')
            ->select();
        echoJson(1,'查询成功',$data);
    }
}