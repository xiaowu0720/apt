<?php


namespace app\collection\model;


use think\Db;
use think\Model;

class Collectionm extends Model
{
    public function add($id, $siteid)
    {
        $reu = Db::table('collection')
            ->where('siteid', $siteid)
            ->select();
        if(!empty($reu)){
            echoJson(0,'该城市已收藏');
        }
        $data = [
            'id'     => $id,
            'siteid' => $siteid,
        ];

        Db::table('collection')->insert($data);
        echoJson('1','收藏成功');
    }

    public function colist($id)
    {
        $redis = init_redis();
        $temp = Db::table('collection')
            ->alias('a')
            ->join('site b', 'a.siteid = b.id')
            ->field('b.name')
            ->select();
        $data = [];
        foreach ($temp as $temp1) {
            $data1 = $redis->get($temp1['name']);
            if (empty($data1)) {
                $result = array(
                    'date' => date('Y-m-d'),
                    'time' => date('H:i:s'),
                    'temperature' => 0,
                    'humidity' => 0,
                    'pm25' => 0,
                    'pm10' => 0,
                    'co' => 0,
                    'co2' => 0,
                    'aqi' => 0,
                    'api' => 0,
                    'primarypollutants' => 0,
                    'color' => 0
                );
            }else {
                $dataArray = explode(' ', $data1);

                $keys = array('date', 'time', 'temperature', 'humidity', 'pm2.5', 'pm10', 'co', 'co2', 'aqi', 'api', 'primarypollutants', 'color');

                $result = array_combine($keys, $dataArray);
            }

            $temp2 = [
                'name' => $temp1['name'],
                'data' => $result,
            ];
            $data[] = $temp2;
        }
        echoJson(1,'查询成功',$data);
    }

    public function del($id,$siteid)
    {
        Db::table('collection')
            ->where('id', $id)
            ->where('siteid', $siteid)
            ->delete();
        echoJson('1','取消收藏成功');
    }

    public function sitelist($id,$province, $county)
    {
        $redis = init_redis();
        $temp = Db::table('site')
            ->where('province', $province)
            ->where('county', $county)
            ->order('id','desc')
            ->select();
        $data = [];
        $temp2 = Db::table('collection')
            ->where('id',$id)
            ->order('siteid','desc')
            ->select();
        $index = 0;
        foreach ($temp as $temp1) {
            $data1 = $redis->get($temp1['name']);

            $dataArray = explode(' ', $data1);

            $keys = array('date', 'time', 'temperature', 'humidity', 'pm2.5', 'pm10', 'co', 'co2', 'aqi', 'api', 'primarypollutants', 'color');

            $result = array_combine($keys, $dataArray);
            if( $index < count($temp2) && $temp1['id'] == $temp2[$index++]['siteid']){
                $temp3 = [
                    'siteid'  => $temp1['id'],
                    'site'    => $temp1['name'],
                    'address' => $temp1['address'],
                    'state'   => true,
                    'data'    => $result
                ];
            }else{
                $temp3 = [
                    'siteid'  => $temp1['id'],
                    'site'    => $temp1['name'],
                    'address' => $temp1['address'],
                    'state'   => false,
                    'data'    => $result
                ];
            }
            $data[] = $temp3;
        }
        echoJson(1,'查询成功',$data);
    }
}