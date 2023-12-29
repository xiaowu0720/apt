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
    public function getdata()
    {
        $this->info = $_GET;
        $site = getsitename($this->info['id']);
        $redis = init_redis();
        $time = date('H');
        $addr = Db::table('equipment')
            ->where('site',$site)
            ->field('device_address')
            ->select();
        $data = $redis->hGet($addr[0]['device_address'],$time);
        $dataArray = explode(' ', $data);
        $keys = array('temperature', 'humidity', 'pm25', 'pm10', 'co', 'co2', 'aqi', 'api', 'primarypollutants', 'color');
        $result = array_combine($keys, $dataArray);
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
    public function mothdata(Request $request) {
        $site = getsitename($request->param('id'));
        $start_date = $request->param('start_date');
        $end_date = $request->param('end_date');

        // 使用 Carbon 类创建开始和结束日期对象
        $startDate = Carbon::parse($start_date);
        $endDate = Carbon::parse($end_date);

        // 获取上一年的开始和结束日期，但月份保持一致
        $lastYearStartDate = $startDate->copy()->subYear();
        $lastYearEndDate = $endDate->copy()->subYear();
        $startDate->subMonth();


        // 初始化月份数组
        $months = [];

        // 循环生成每个月的日期范围
        while ($startDate->lte($endDate)) {
            $months[] = $startDate->format('Ym').'apt';
            $startDate->addMonth(); // 移动到下一个月
        }

        // 初始化上一年的月份数组
        $lastYearMonths = [];

        // 循环生成上一年每个月的日期范围， starting from the current month of last year
        while ($lastYearStartDate->lte($lastYearEndDate)) {
            $lastYearMonths[] = $lastYearStartDate->format('Ym').'apt';
            $lastYearStartDate->addMonth(); // 移动到下一个月
        }

        $data = [];
        $sum = [];
        $sum['pm25'] = 0;
        $sum['pm10'] = 0;
        $sum['co'] = 0;
        $sum['co2'] = 0;
        $count = 0;


        // 处理今年的数据
        foreach ($months as $temp) {
            // 检查表是否存在
            $tableExists = Db::query("SHOW TABLES LIKE '{$temp}'");

            if ($tableExists) {
                $result = Db::table($temp)
                    ->where('sitename',$site)
                    ->field([
                        'AVG(temperature) AS temperature',
                        'AVG(humidity) AS humidity',
                        'AVG(pm25) AS pm25',
                        'AVG(pm10) AS pm10',
                        'AVG(co) AS co',
                        'AVG(co2) AS co2',
                        'AVG(aqi) AS aqi',
                        'AVG(api) AS api',
                    ])
                    ->find();
                $sum['pm25'] += $result['pm25'];
                $sum['pm10'] += $result['pm10'];
                $sum['co'] += $result['co'];
                $sum['co2'] += $result['co2'];
                $count++;
                $data['now'][substr($temp,0,6)] = $result;
            } else {
                // 表不存在，设置默认值为 0
                $data['now'][substr($temp,0,6)] = [
                    'temperature' => 0,
                    'humidity' => 0,
                    'pm25' => 0,
                    'pm10' => 0,
                    'co' => 0,
                    'co2' => 0,
                    'aqi' => 0,
                    'api' => 0,
                ];
            }
        }
        // 处理上一年的数据
        foreach ($lastYearMonths as $temp) {
            // 检查表是否存在
            $tableExists = Db::query("SHOW TABLES LIKE '{$temp}'");

            if ($tableExists) {
                $result = Db::table($temp)
                    ->where('sitename',$site)
                    ->field([
                        'AVG(temperature) AS temperature',
                        'AVG(humidity) AS humidity',
                        'AVG(pm25) AS pm25',
                        'AVG(pm10) AS pm10',
                        'AVG(co) AS co',
                        'AVG(co2) AS co2',
                        'AVG(aqi) AS aqi',
                        'AVG(api) AS api',
                    ])
                    ->find();
                $data['last'][substr($temp,0,6)] = $result;
            } else {
                // 表不存在，设置默认值为 0
                $data['last'][substr($temp,0,6)] = [
                    'temperature' => 0,
                    'humidity' => 0,
                    'pm25' => 0,
                    'pm10' => 0,
                    'co' => 0,
                    'co2' => 0,
                    'aqi' => 0,
                    'api' => 0,
                ];
            }
        }
        $sum['pm25'] /= $count;
        $sum['pm10'] /= $count;
        $sum['co'] /= $count;
        $sum['co2'] /= $count;

        echoJson(1, '查询成功', $data);
    }

    public function ranking(Request $request) {
        $manner = $request->param('manner', 'aqi');
        if (empty($manner)) {
            $manner = 'aqi';
        }
        $result1 = Db::table('equipment')
            ->where('state','1')
            ->select();
        $data = [];
        $index = 0;
        foreach ($result1 as $temp) {
            $redis = init_redis();
            $time = date('H');
            $data1 = $redis->hGet($temp['device_address'],$time);
            $dataArray = explode(' ', $data1);
            $keys = array('temperature', 'humidity', 'pm25', 'pm10', 'co', 'co2', 'aqi', 'api', 'primarypollutants', 'color');
            $result = array_combine($keys, $dataArray);
            $data[$index++] = [
                'site'  => $temp['site'],
                'field' => $manner,
                'value' => $result[$manner],
            ];
        }
        usort($data, function ($a, $b) {
            return $a['value'] - $b['value'];
        });
        echoJson(1,'查询成功', $data);
    }

    public function calendardata(Request $request)
    {
        $site = getsitename($request->param('id'));
        $date = $request->param('date');
        try {
            $date = new \DateTime($date);
            $date = $date->format('Ym');
            $data = Db::table($date.'apt')
                ->field('record_date as date, aqi')
                ->where('sitename',$site)
                ->select();
            echoJson(1,'查询成功', $data);
        }catch (Exception $e){
            echoJson(1,'查询成功');
        }
    }

}