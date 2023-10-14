<?php


namespace app\site\model;


use think\Db;
use think\Model;

class SiteModel extends Model
{
    public function siteindex($page, $count)
    {
        $data = Db::table('site')
        ->page($page, $count)
        ->select();
        $count = Db::table('site')->select();
        echoJson(1, '查询成功',$data,$page, count($count));
    }


    public function sitesave($data)
    {
        $temp = Db::table('citylist')
            ->where('province', $data['province'])
            ->where('cityname', $data['county'])
            ->select();
        if(empty($temp)){
            echoJson(0,'请先添加城市');
        }
        $rel = Db::table('site')
            ->where('name', $data['name'])
            ->select();
        if(!empty($rel)){
            echoJson(0,'已经存在该站点名称');
        }
        Db::table('site')->insert($data);
        echoJson(1,'站点创建成功');
    }

    public function read($name, $province, $county, $address,$page,$count)
    {
        $query = Db::table('site');
        $sum = Db::table('site');

        if (!empty($name)) {
            $query->where('name', 'like', '%'.$name.'%');
            $sum->where('name', 'like', '%'.$name.'%');
        }
        if (!empty($province)) {
            $query->where('province', 'like', '%'.$province.'%');
            $sum->where('province', 'like', '%'.$province.'%');
        }
        if (!empty($county)) {
            $query->where('county', 'like', '%'.$county.'%');
            $sum->where('county', 'like', '%'.$county.'%');
        }
        if (!empty($address)) {
            $query->where('address', 'like', '%'.$address.'%');
            $sum->where('address', 'like', '%'.$address.'%');
        }
        $data = $query
            ->page($page,$count)
            ->select();
        $count = $sum->count();
        echoJson(1,'查询成功',$data,$page,$count);
    }

    public function siteupdate($id, $data)
    {
        Db::table('site')->where('id',$id)->update($data);
        echoJson(1,'更新成功');
    }
}