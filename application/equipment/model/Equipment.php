<?php


namespace app\equipment\model;


use app\equipment\validate\Vequipment;
use think\Db;
use think\Model;

class Equipment extends Model
{
    public function listequipment($state, $device_address, $equipmentname, $site, $page, $count)
    {
        $tablename = getTableName();
        $data = Db::table('equipment');
        $temp = Db::table('equipment');

        if (empty($page)) {
            $page = 1;
        }
        if (empty($count)) {
            $count = 10;
        }
        if(!empty($state)) {
            $data->where('state',$state);
            $temp->where('state',$state);
        }
        if(!empty($device_address)) {
            $data->where('device_address','like','%'.$device_address.'%');
            $temp->where('device_address','like','%'.$device_address.'%');
        }
        if(!empty($equipmentname)) {
            $data->where('equipmentname','like','%'.$equipmentname.'%');
            $temp->where('equipmentname','like','%'.$equipmentname.'%');
        }
        if(!empty($site)) {
            $data->where('site','like','%'.$site.'%');
            $temp->where('site','like','%'.$site.'%');
        }
        $data = $data
            ->page($page,$count)
            ->select();
        $count = $temp
            ->count();

        echoJson(1,'查询成功',$data,$page,$count);
    }

    public function bindequipent($id, $site)
    {
        $reu=Db::table('site')->where('name',$site)->select();
        if(empty($reu)){
            echoJson(0,'请先添加站点');
        }
        $data=[
            'site' => $site
        ];
        Db::table('equipment')->where('id',$id)->update($data);
        echoJson(1,'绑定成功');
    }

    public function addequipment($device_address,$equipmentname,$site){
        $count=Db::table('equipment')->count();
        $data=[
            'id'             => $count+1,
            'device_address' => $device_address,
            'state'          => '1',
            'equipmentname'  => $equipmentname,
            'date'           => date('Y-m-d H:i:s'),
            'site'           => $site
        ];
        $validate=new Vequipment();
        if(!$validate->check($data)){
            echoJson(0,$validate->getError());
        }
        $pd=Db::table('equipment')->where('device_address',$data['device_address'])->select();
        if(!empty($pd)){
            echoJson(0,'已存在该设备');
        }
        $pd=Db::table('equipment')->where('equipmentname',$data['equipmentname'])->select();
        if(!empty($pd)){
            echoJson(0,'设备名不允许重复');
        }
        Db::table('equipment')->insert($data);
        echoJson(1,'设备添加成功');
    }

    public function updateequ($id,$equipmentname, $device_address, $site, $state)
    {
        $data = [];
        if (!empty($state)) {
            $data['state'] = $state;
        }

        if (!empty($equipmentname)) {
            $data['equipmentname'] = $equipmentname;
        }

        if (!empty($device_address)) {
            $data['device_address'] = $device_address;
        }

        if (!empty($site)) {
            $data['site'] = $site;
        }
        Db::table('equipment')->where('id', $id)->update($data);

        echoJson(1,'更新成功');
    }
}