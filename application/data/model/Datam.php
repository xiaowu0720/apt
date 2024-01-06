<?php


namespace app\data\model;


use Carbon\Carbon;
use think\Db;
use think\Exception;
use think\Model;

class Datam extends Model
{
    public function yeardata($year,$site) {

    }

    public function  calendardata($site, $date) {
        try {
            $date = new \DateTime($date);
            $date = $date->format('Ym');

            $data = Db::table($date.'apt')
                ->field('record_date as date, CAST(aqi AS SIGNED) as aqi')
                ->where('sitename', $site)
                ->select();

            echoJson(1, '查询成功', $data);
        } catch (Exception $e) {
            echoJson(1, '查询成功');
        }
    }

    public function ranking($manner) {
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
                'value' => (int)$result[$manner],
            ];
        }
        usort($data, function ($a, $b) {
            return $a['value'] - $b['value'];
        });
        echoJson(1,'查询成功', $data);
    }

    public function monthdata($site, $start_date, $end_date) {
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
        // 处理今年的数据
        foreach ($months as $temp)
        {
            // 检查表是否存在
            $tableExists = Db::query("SHOW TABLES LIKE '{$temp}'");

            if ($tableExists) {
                $result = Db::table($temp)
                    ->where('sitename', $site)
                    ->field([
                        'CAST(AVG(temperature) AS SIGNED) AS temperature',
                        'CAST(AVG(humidity) AS SIGNED) AS humidity',
                        'CAST(AVG(pm25) AS SIGNED) AS pm25',
                        'CAST(AVG(pm10) AS SIGNED) AS pm10',
                        'CAST(AVG(co) AS SIGNED) AS co',
                        'CAST(AVG(co2) AS SIGNED) AS co2',
                        'CAST(AVG(aqi) AS SIGNED) AS aqi',
                        'CAST(AVG(api) AS SIGNED) AS api',
                    ])
                    ->find();
                if ($result['temperature'] == Null){
                    $data['now'][substr($temp, 0, 6)] = [
                        'temperature' => 0,
                        'humidity' => 0,
                        'pm25' => 0,
                        'pm10' => 0,
                        'co' => 0,
                        'co2' => 0,
                        'aqi' => 0,
                        'api' => 0,
                    ];
                }else{
                    $sum['pm25'] += $result['pm25'];
                    $sum['pm10'] += $result['pm10'];
                    $sum['co'] += $result['co'];
                    $sum['co2'] += $result['co2'];
                    $count++;
                    $data['now'][substr($temp, 0, 6)] = $result;
                }
            } else {
                // 表不存在，设置默认值为 0
                $data['now'][substr($temp, 0, 6)] = [
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
        foreach ($lastYearMonths as $temp)
        {
            // 检查表是否存在
            $tableExists = Db::query("SHOW TABLES LIKE '{$temp}'");

            if ($tableExists) {
                $result = Db::table($temp)
                    ->where('sitename', $site)
                    ->field([
                        'CAST(AVG(temperature) AS SIGNED) AS temperature',
                        'CAST(AVG(humidity) AS SIGNED) AS humidity',
                        'CAST(AVG(pm25) AS SIGNED) AS pm25',
                        'CAST(AVG(pm10) AS SIGNED) AS pm10',
                        'CAST(AVG(co) AS SIGNED) AS co',
                        'CAST(AVG(co2) AS SIGNED) AS co2',
                        'CAST(AVG(aqi) AS SIGNED) AS aqi',
                        'CAST(AVG(api) AS SIGNED) AS api',
                    ])
                    ->find();
                if ($result['temperature'] == Null){
                    $data['last'][substr($temp, 0, 6)] = [
                        'temperature' => 0,
                        'humidity' => 0,
                        'pm25' => 0,
                        'pm10' => 0,
                        'co' => 0,
                        'co2' => 0,
                        'aqi' => 0,
                        'api' => 0,
                    ];
                }else{
                    $data['last'][substr($temp, 0, 6)] = $result;
                }
            } else {
                // 表不存在，设置默认值为 0
                $data['last'][substr($temp, 0, 6)] = [
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

        $sum['pm25'] = (int)($sum['pm25'] / $count);
        $sum['pm10'] = (int)($sum['pm10'] / $count);
        $sum['co'] = (int)($sum['co'] / $count);
        $sum['co2'] = (int)($sum['co2'] / $count);

        echoJson(1, '查询成功', $data);
    }

}