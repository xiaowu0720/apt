<?php
namespace app\data\controller;


use app\common\model\Jwt_base;
use app\data\model\Datam;
use think\App;
use think\Controller;
use think\Db;
use think\Request;

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
        $site = $this->info['sitename'];
        $redis = init_redis();
        $data = $redis->get($site);

        if ($data === false) {
            $result = array(
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'temperature' => 0,
                'humidity' => 0,
                'pm2.5' => 0,
                'pm10' => 0,
                'co' => 0,
                'co2' => 0,
                'aqi' => 0,
                'api' => 0,
                'primarypollutants' => 0,
                'color' => 0
            );
        } else {
            $dataArray = explode(' ', $data);
            $keys = array('date', 'time', 'temperature', 'humidity', 'pm2.5', 'pm10', 'co', 'co2', 'aqi', 'api', 'primarypollutants', 'color');
            $result = array_combine($keys, $dataArray);
        }

        echoJson(1, '查询成功', $result);
    }
    //获取昨日数据(城市列表)
    public function yesterdaydata()
    {
        $this->info = $_GET;
        $province = $this->info['province'];
        $field = $this->info['field'];
        $field1 = $field;
        if ($field === 'PM2.5') {
            $field1 = 'pm25';
        }
        if ($field === 'PM10') {
            $field1 = 'pm10';
        }
        $field1 = strtolower($field1);
        if ($field1 === 'co2') {
            $field1 = 'Co2';
        }
        if ($field1 === 'co') {
            $field1 = 'Co';
        }
        $date = date('Y-m-d',time()-24*60*60);
        $temp = Db::table('data_day')
            ->where('province', $province)
            ->where('date', $date)
            ->select();
        $data = [];
        foreach ($temp as $temp1) {
            $temp2 = [
                'city' => $temp1['county'],
                $field => $temp1[$field1]
            ];
            $data[] = $temp2;
        }
        // 在这里你可以进一步处理 $data 数组，比如打印输出或者进行其他操作
        echoJson(1,'查询成功',$data);
    }
    //获取本月数据
    public function monthdata() {
        $this->info = $_GET;
        $province = $this->info['province'];
        $field = $this->info['field'];
        $field1 = $field;
        if ($field === 'PM2.5') {
            $field1 = 'pm25';
        }
        if ($field === 'PM10') {
            $field1 = 'pm10';
        }
        $field1 = strtolower($field1);
        if ($field1 === 'co2') {
            $field1 = 'Co2';
        }
        if ($field1 === 'co') {
            $field1 = 'Co';
        }
        $equipment = Db::table('citylist')
            ->where('province', $province)
            ->select();
        $data = [];

        $currentMonth = date('Y-m'); // 获取当前年月，格式为 "YYYY-MM"

        foreach ($equipment as $temp) {
            $temp1 = Db::table('data_day')
                ->where('county', $temp['cityname'])
                ->where('date', 'like', $currentMonth.'%') // 查询本月数据，日期以当前年月开头
                ->select();
            // 计算字段的平均值
            $sum = 0;
            $count = count($temp1);
            foreach ($temp1 as $item) {
                $sum += $item[$field1];
            }
            if ($count != 0) {
                $average = $sum / $count;
            }else{
                $average = 0;
            }
            $temp2=[
                'city' => $temp['cityname'],
                $field => $average
            ];
            $data[]=$temp2;
        }

        // 在这里你可以进一步处理 $data 数组，比如打印输出或者进行其他操作
        echoJson(1, '查询成功', $data);
    }
    //获取今年数据
    public function yeardata() {
        $this->info = $_GET;
        $province = $this->info['province'];
        $field = $this->info['field'];
        $field1 = $field;
        if ($field === 'PM2.5') {
            $field1 = 'pm25';
        }
        if ($field === 'PM10') {
            $field1 = 'pm10';
        }
        $field1 = strtolower($field1);
        if ($field1 === 'co2') {
            $field1 = 'Co2';
        }
        if ($field1 === 'co') {
            $field1 = 'Co';
        }
        $equipment = Db::table('citylist')
            ->where('province', $province)
            ->select();
        $data = [];

        $currentMonth = date('Y');
        foreach ($equipment as $temp) {
            $temp1 = Db::table('data_day')
                ->where('county', $temp['cityname'])
                ->where('date', 'like', $currentMonth.'%')
                ->select();
            // 计算字段的平均值
            $sum = 0;
            $count = count($temp1);
            foreach ($temp1 as $item) {
                $sum += $item[$field1];
            }
            if ($count != 0) {
                $average = $sum / $count;
            }else{
                $average = 0;
            }
            $temp2=[
                'city' => $temp['cityname'],
                $field => $average
            ];
            $data[]=$temp2;
        }

        // 在这里你可以进一步处理 $data 数组，比如打印输出或者进行其他操作
        echoJson(1, '查询成功', $data);
    }
    //获取去年数据
    public function lastYeardata() {
        $this->info = $_GET;
        $province = $this->info['province'];
        $field = $this->info['field'];
        $field1 = $field;
        if ($field === 'PM2.5') {
            $field1 = 'pm25';
        }
        if ($field === 'PM10') {
            $field1 = 'pm10';
        }
        $field1 = strtolower($field1);
        if ($field1 === 'co2') {
            $field1 = 'Co2';
        }
        if ($field1 === 'co') {
            $field1 = 'Co';
        }
        $equipment = Db::table('citylist')
            ->where('province', $province)
            ->select();
        $data = [];

        $currentMonth = date('Y', strtotime('-1 year'));;
        foreach ($equipment as $temp) {
            $temp1 = Db::table('data_day')
                ->where('county', $temp['cityname'])
                ->where('date', 'like', $currentMonth.'%')
                ->select();
            // 计算字段的平均值
            $sum = 0;
            $count = count($temp1);
            foreach ($temp1 as $item) {
                $sum += $item[$field1];
            }
            if ($count != 0) {
                $average = $sum / $count;
            }else{
                $average = 0;
            }
            $temp2=[
                'city' => $temp['cityname'],
                $field => $average
            ];
            $data[]=$temp2;
        }

        // 在这里你可以进一步处理 $data 数组，比如打印输出或者进行其他操作
        echoJson(1, '查询成功', $data);
    }
    //城市列表实时数据
    public function today()
    {
        $this->info = $_GET;
        $province = $this->info['province'];
        $field = $this->info['field'];
        $field1 = $field;
        if ($field === 'PM2.5') {
            $field1 = 'pm25';
        }
        if ($field === 'PM10') {
            $field1 = 'pm10';
        }
        $field1 = strtolower($field1);
        if ($field1 === 'co2') {
            $field1 = 'Co2';
        }
        if ($field1 === 'co') {
            $field1 = 'Co';
        }
        $city = Db::table('citylist')
            ->where('province', $province)
            ->select();
        $data1 = [];

        foreach ($city as $city1) {
            $site = Db::table('site')->where('county',$city1['cityname'])->select();
            if (!empty($site)) {
                $sum = 0;
                $count = 0;
                foreach ($site as $site1) {
                    $redis = init_redis();
                    $data = $redis->get($site1['name']);
                    if($data == false){
                        continue;
                    }
                    $dataArray = explode(' ', $data);

                    $keys = array('date', 'time', 'temperature', 'humidity', 'pm25', 'pm10', 'Co', 'Co2', 'aqi', 'api', 'primarypollutants', 'color');

                    $result = array_combine($keys, $dataArray);
                    $sum += $result[$field1];
                    $count++;
                }
                $temp = [
                    'city' => $city1['cityname'],
                    $field => $count == 0 ? 0 : $sum / $count
                ];
                $data1[] = $temp;
            }else {
                $temp = [
                    'city' => $city1['cityname'],
                    $field => 0
                ];
                $data1[] = $temp;
            }
        }


        // 在这里你可以进一步处理 $data 数组，比如打印输出或者进行其他操作
        echoJson(1, '查询成功', $data1);
    }
    //获得30日数据
    public function day30data() {
        $this->jwt->check();
        $this->info = $_GET;
        $province = $this->info['province'];
        $cityname = $this->info['cityname'];

        $startDate = date('Y-m-d', strtotime('-30 days')); // 获取当前日期前30天的日期，格式为 "YYYY-MM-DD"
        $endDate = date('Y-m-d', strtotime('-1 day')); // 获取当前日期的昨天日期，格式为 "YYYY-MM-DD"

        $data = Db::table('data_day')
            ->where('province',$province)
            ->where('county',$cityname)
            ->whereBetween('date',[$startDate,$endDate])
            ->select();

        // 在这里你可以进一步处理 $data 数组，比如打印输出或者进行其他操作
        echoJson(1, '查询成功', $data);
    }
    //72小时数据  sitedata表(变化趋势)
    public function get72data(){
        $this->info = $_GET;
        $site = $this->info['site'];
        $field=$this->info['field'];
        $data = [];
        $time=time();
        $field1 = $field;
        if ($field === 'PM2.5') {
            $field1 = 'pm25';
        }
        if ($field === 'PM10') {
            $field1 = 'pm10';
        }
        $field1 = strtolower($field1);
        if ($field1 === 'co2') {
            $field1 = 'Co2';
        }
        if ($field1 === 'co') {
            $field1 = 'Co';
        }
        for($i=1;$i<=72;$i++){
            $temp=$time-3600;
            $time1=date('Y-m-d H:i:s',$time);
            $time2=date('Y-m-d H:i:s',$temp);
            $reu=Db::table('sitedata')->where('site',$site)->whereBetween('time',[$time2,$time1])->avg($field1);
            $datatemp=[
                'time'=>$time1,
                $field=>$reu
            ];
            $data[]=$datatemp;
            $time=$temp;
        }
        echoJson(1,'查询成功',$data);
    }

    //月度排名
    public function ranking(Request $request)
    {
        $this->jwt->check();
        $province = $request->apram('province');
        $county = $request->param('county');
        $date = $request->param('date');

        $this->data->ranking();
    }

    //24小时  sitedata表(变化趋势)
    public function get24data()
    {
        $this->info = $_GET;
        $site = $this->info['site'];
        $field=$this->info['field'];
        $data = [];
        $time=time();
        $field1 = $field;
        if ($field === 'PM2.5') {
            $field1 = 'pm25';
        }
        if ($field === 'PM10') {
            $field1 = 'pm10';
        }
        $field1 = strtolower($field1);
        if ($field1 === 'co2') {
            $field1 = 'Co2';
        }
        if ($field1 === 'co') {
            $field1 = 'Co';
        }
        for($i=1;$i<=24;$i++){
            $temp=$time-3600;
            $time1=date('Y-m-d H:i:s',$time);
            $time2=date('Y-m-d H:i:s',$temp);
            $reu=Db::table('sitedata')->where('site',$site)->whereBetween('time',[$time2,$time1])->avg($field1);
            $datatemp=[
                'time'=>$time1,
                 $field=>$reu
            ];
            $data[]=$datatemp;
            $time=$temp;
        }
        echoJson(1,'查询成功',$data);
    }

    //30日(变化趋势)
    public function get30data()
    {
        $this->info = $_GET;
        $field = $this->info['field'];
        $site = $this->info['site'];
        $field1 = $field;
        if ($field === 'PM2.5') {
            $field1 = 'pm25';
        }
        if ($field === 'PM10') {
            $field1 = 'pm10';
        }
        $field1 = strtolower($field1);
        if ($field1 === 'co2') {
            $field1 = 'Co2';
        }
        if ($field1 === 'co') {
            $field1 = 'Co';
        }
        $data = Db::table('site')
            ->where('name', $site)
            ->select();
        $currentDate = date('Y-m-d');
        $thirtyDaysAgo = date('Y-m-d', strtotime('-30 days'));
        $temp = Db::table('data_day')
            ->where('province', $data[0]['province'])
            ->where('county', $data[0]['county'])
            ->whereBetween('date', [$thirtyDaysAgo, $currentDate])
            ->select();
        $data = [];
        foreach ($temp as $temp1) {
            $temp2 = [
                'time'  => $temp1['date'],
                $field => $temp1[$field1],
            ];
            $data[] = $temp2;
        }
        echoJson(1,'查询成功',$data);
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
            }
        }
        echoJson(1,'',$data);
    }
}