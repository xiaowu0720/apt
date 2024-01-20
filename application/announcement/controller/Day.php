<?php

namespace app\announcement\controller;

use think\Controller;
use think\Db;
use think\Request;

class Day extends Controller
{
    public function day($id,Request $request)
    {
        $redis = init_redis();
        $time = date('H');
        $addr = Db::table('equipment')
            ->where('sid', $id)
            ->field('device_address')
            ->select();
        if (empty($addr)) {
            echoJson(0,'不存在该站点');
        }
        $data = $redis->hGet($addr[0]['device_address'], $time);
        $dataArray = explode(' ', $data);

        // Define the rounding function
        $roundingFunction = function ($value) {
            return round($value);
        };

        // Exclude the last two values from rounding
        $roundingExcludeLastTwo = function ($key, $value) use ($roundingFunction, $dataArray) {
            if ($key < count($dataArray) - 2) {
                return $roundingFunction($value);
            }
            return $value;
        };

        $keys = array('temperature', 'humidity', 'pm25', 'pm10', 'co', 'co2', 'aqi', 'api', 'primarypollutants', 'color');
        $result = array_combine($keys, array_map($roundingExcludeLastTwo, array_keys($dataArray), $dataArray));
        $temp = Db::table('announcement')
            ->where('maxaqi','>=',$result['aqi'])
            ->where('minaqi','<=',$result['aqi'])
            ->select();
        if (empty($temp)) {
            echoJson(0,'没有当前范围的公告');
        }
        echoJson(1,'查询成功',$temp[0]);
    }

}