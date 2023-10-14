<?php


namespace app\city\model;


use think\Db;

class Citymodel
{
    public function addcity($data)
    {
        if(empty($data['cityname'])||empty($data['province'])){
            echoJson(0,'请完善城市信息');
        }
        $reu=Db::table('citylist')->where('cityname',$data['cityname'])->select();
        if(!empty($reu)){
            if($data['province']==$reu[0]['province']){
                echoJson(0,'该城市已存在');
            }
        }
        Db::table('citylist')->insert($data);
        echoJson(1,'添加成功');
    }

    //删除城市
    public function delcity($id)
    {
        Db::table('citylist')->where('id',$id)->delete();
        Db::table('citylist')->where('id', '>', $id)->dec('id')->update();
        echoJson(1,"删除成功");
    }
    //更新城市信息
    public function revisecity($id,$cityname,$province)
    {
        $data = [];
        if (!empty($cityname)) {
            $data['cityname'] = $cityname;
        }
        if (!empty($province)) {
            $data['province'] = $province;
        }
        Db::table('citylist')->where('id',$id)->update($data);
        echoJson(1,"更新成功");
    }

    public function readcity($cityname, $province, $page, $count)
    {
        $data = Db::table('citylist');
        $temp = Db::table('citylist');

        if (!empty($cityname)) {
            $data->where('cityname','like', '%'.$cityname.'%');
            $temp->where('cityname','like', '%'.$cityname.'%');
        }

        if (!empty($province)) {
            $data->where('province','like', '%'.$province.'%');
            $temp->where('province','like', '%'.$province.'%');
        }

        $data = $data
            ->page($page, $count)
            ->select();
        $temp = $temp->count();
        echoJson(1,'查询成功',$data,$page,$temp);
    }
}