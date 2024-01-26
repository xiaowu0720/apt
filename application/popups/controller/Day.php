<?php

namespace app\popups\controller;

use app\popup\model\popupm;
use think\App;
use think\Controller;
use think\Db;
use think\Request;
use function app\data\controller;

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
            echoJson(0,'The site does not exist');
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
        $temp = Db::table('send_message')
            ->where('minaqi','<=',$result['aqi'])
            ->where('maxaqi','>=',$result['aqi'])
            ->select();
        if (empty($temp)) {
            echoJson(0,'There is no pop-up window for the current range');
        }
        echoJson(1,'The query succeeded',$temp[0]);
    }
}