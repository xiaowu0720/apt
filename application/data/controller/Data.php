<?php
namespace app\data\controller;


use app\common\model\Jwt_base;
use app\data\model\Datam;
use think\App;
use think\Controller;
use think\Db;
use think\Exception;
use think\Request;
use Carbon\Carbon;

class Data extends Controller{
    public $info;
    public $jwt;
    public $data;
    public function __construct(App $app = null)
    {
        $this->data = new Datam();
        parent::__construct($app);
    }

    //获取实时数据
    public function getdata(Request $request, $id)
    {
        $redis = init_redis();
        $time = date('H');
        $addr = Db::table('equipment')
            ->where('sid', $id)
            ->field('device_address')
            ->select();
        if (empty($addr)) {
            echoJson(0,'该站点没有添加设备');
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

        echoJson(1, '查询成功', $result);
    }
    //获取站点最近位置
    public function location()
    {
        $this->info = $_GET;
        $longitude = $this->info['longitude'];
        $latitude = $this->info['latitude'];
        $tempdata = Db::table('site')
            ->select();
        if(empty($tempdata)){
            echoJson('0','当前城市没有站点');
        }
        $min = PHP_FLOAT_MAX;
        $data = [];
        foreach ($tempdata as $temp){
            $temp1 = distance($longitude, $latitude, $temp['longitude'], $temp['latitude']);
            if($temp1 < $min){
                $data = $temp;
                $min = $temp1;
            }
        }
        echoJson(1,'',$data);
    }
    //月度数据
    public function mothdata(Request $request, $id)
    {
        $site = getdevice_address($id);
        $start_date = $request->param('start_date');
        $end_date = $request->param('end_date');

        $this->data->monthdata($site, $start_date, $end_date);

    }

    public function ranking(Request $request)
    {
        $manner = $request->param('manner', 'aqi');
        $sort = $request->param('sort','desc');
        $this->data->ranking($manner);
    }

    public function calendardata(Request $request, $id)
    {
        $date = $request->param('date');
        $this->data->calendardata($id,$date);
    }

    public function yeardata(Request $request, $id)
    {
        $year = $request->param('year');
        $site = getsitename($id);
        $data = [];
        $data1 = [];

        for ($month = 1; $month <= 12; $month++) {
            $formattedMonth = sprintf("%02d", $month);
            $tname = $year . $formattedMonth;
            try {
                $result = Db::table($tname.'apt')
                    ->where('sitename', $site)
                    ->field('max(pm25) as pm25, max(pm10) as pm10, max(co) as co, max(co2) as co2, avg(pm25) as avg_pm25, avg(pm10) as avg_pm10, avg(co) as avg_co, avg(co2) as avg_co2')
                    ->select();
                $result1 = Db::table($tname.'apt')
                    ->where('sitename', $site)
                    ->field('aqi')
                    ->select();
                $data1[$month] = $result1;
                foreach ($result as &$row) {
                    foreach ($row as $key => &$value) {
                        $value = intval($value);
                    }
                }

                $data[$month] = $result[0];
            } catch (Exception $e) {
                $data[$month] = [
                    'pm25'     => 0,
                    'pm10'     => 0,
                    'co'       => 0,
                    'co2'      => 0,
                    'avg_pm25' => 0,
                    'avg_pm10' => 0,
                    'avg_co'   => 0,
                    'avg_co2'  => 0
                ];
            }
        }
        $sum_pm25 = 0;
        $sum_pm10 = 0;
        $sum_co = 0;
        $sum_co2 = 0;
        foreach ($data as $temp) {
            $sum_pm25 += $temp['avg_pm25'];
            $sum_pm10 += $temp['avg_pm10'];
            $sum_co += $temp['avg_co'];
            $sum_co2 += $temp['avg_co2'];
        }
        $data['sum_pm25'] = (int)$sum_pm25;
        $data['sum_pm10'] = (int)$sum_pm10;
        $data['sum_co'] = (int)$sum_co;
        $data['sum_co2'] = (int)$sum_co2;
        $data['01'] = 0;
        $data['02'] = 0;
        $data['03'] = 0;
        $data['04'] = 0;
        $data['05'] = 0;
        $data['06'] = 0;
        foreach ($data1 as $temp) {
            foreach ($temp as $temp1) {
                if ($temp1['aqi'] <= 50) {
                    $data['01']++;
                }elseif ($temp1['aqi'] <= 100) {
                    $data['02']++;
                }elseif ($temp1['aqi'] <= 150) {
                    $data['03']++;
                }elseif ($temp1['aqi'] <= 200) {
                    $data['04']++;
                }elseif ($temp1['aqi'] <= 300) {
                    $data['05']++;
                }else {
                    $data['06']++;
                }
            }
        }
        echoJson(1,'查询成功',$data);
    }

}