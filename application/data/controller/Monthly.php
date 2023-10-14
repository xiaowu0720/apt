<?php


namespace app\data\controller;


use think\Controller;
use think\Db;
use think\Request;

class Monthly extends Controller
{
    public function data(Request $request)
    {
        $province = $request->param('province');
        $county = $request->param('county');
        $date = $request->param('date');

        $temp = Db::table('site')
            ->where('province', $province)
            ->where('county', $county)
            ->select();

        $data = [];
        foreach ($temp as $temp1) {
            $reu = Db::table('siteday')
                ->where('site', $temp1['name'])
                ->where('date','like' ,$date.'%')
                ->select();
            $aqisum = 0;
            $count = 0;
            $pm25 = 0;
            $pm10 = 0;
            foreach ($reu as $temp2) {
                $aqisum += $temp2['aqi'];
                if ($temp2['primarypollutants'] == 'pm2.5') {
                    $pm25 ++;
                }else {
                    $pm10 ++;
                }
                $count ++;
            }
            if ($count == 0) {
                $temp3 = [
                    'site'              => $temp1['name'],
                    'aqi'               => 0,
                    'primarypollutants' => $pm10>$pm25 ? 'pm10' : 'pm2.5',
                ];
            }else {
                $temp3 = [
                    'site'              => $temp1['name'],
                    'aqi'               => $aqisum / $count,
                    'primarypollutants' => $pm10>$pm25 ? 'pm10' : 'pm2.5',
                ];
            }
            $data = $temp3;
        }

        echoJson(1,'查询成功',$data);
    }
}