<?php


namespace app\popups\model;


use think\Db;
use think\Model;

class Popupsmodel extends Model
{
    public function popindex($page, $count)
    {
        $data = Db::table('send_message')->page($page, $count)->select();
        $count = Db::table('send_message')->count();

        echoJson(1, '查询成功', $data, $page, $count);
    }

    public function addpop($data)
    {
        $data = Db::table('send_message')->insert($data);
        echoJson(1, '新增成功');
    }
}