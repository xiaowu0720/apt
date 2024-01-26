<?php


namespace app\site\model;


use think\Db;
use think\Model;

class SiteModel extends Model
{
    public function siteindex($page, $count, $name, $address, $province, $county)
    {
        $data = Db::table('site');
        $count1 = Db::table('site');
        if (!empty($name)) {
            $data->where('name','like','%'.$name.'%');
            $count1->where('name','like','%'.$name.'%');
        }
        if (!empty($address)) {
            $data->where('address','like','%'.$address.'%');
            $count1->where('address','like','%'.$address.'%');
        }
        if (!empty($province)) {
            $data->where('province','like','%'.$province.'%');
            $count1->where('province','like','%'.$province.'%');
        }
        if (!empty($name)) {
            $data->where('county','like','%'.$county.'%');
            $count1->where('county','like','%'.$county.'%');
        }
        $data = $data->page($page, $count)->select();
        $count1 = $count1->count();
        echoJson(1, 'The query succeeded',$data,$page, $count1);
    }


    public function sitesave($data)
    {
        $temp = Db::table('citylist')
            ->where('id', $data['cityid'])
            ->select();
        if(empty($temp)){
            echoJson(0,'Please add the city first');
        }
        $data['province'] = $temp[0]['province'];
        $data['county'] = $temp[0]['cityname'];
        $rel = Db::table('site')
            ->where('name', $data['name'])
            ->select();
        if(!empty($rel)){
            echoJson(0,'The site name already exists');
        }
        Db::table('site')->insert($data);
        echoJson(1,'The site is created successfully');
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
        echoJson(1,'The query succeeded',$data,$page,$count);
    }

    public function siteupdate($id, $data)
    {
        $rel = Db::table('site')
            ->where('name', $data['name'])
            ->select();
        if(!empty($rel) && $rel[0]['name'] != $data['name']){
            echoJson(0,'The site name already exists');
        }
        Db::table('site')->where('id',$id)->update($data);
        echoJson(1,'The update was successful');
    }
}