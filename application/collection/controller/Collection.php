<?php


namespace app\collection\controller;


use app\collection\model\Collectionm;
use app\common\model\Jwt_base;
use think\App;
use think\Controller;
use think\Db;
use think\Request;
use think\Route;

class Collection extends Controller //收藏
{
    public $jwt;
    public $coll;
    public function __construct(App $app = null)
    {
        $this->jwt = new Jwt_base();
        $this->coll = new Collectionm();
        parent::__construct($app);
    }

    public function sitelist(Request $request)
    {
        $id = $request->param('id');
        $province = $request->param('province');
        $county = $request->param('county');
        $this->coll->sitelist($id,$province, $county);
    }

    public function add(Request $request)
    {
        $id = ($request->data)['id'];
        $siteid = $request->param('siteid');
        $this->coll->add($id,$siteid);
    }

    public function colist(Request $request)
    {
        $id = ($request->data)['id'];
        $this->coll->colist($id);
    }

    public function del(Request $request)
    {
        $id = ($request->data)['id'];
        $siteid = $request->param('siteid');

        $this->coll->del($id,$siteid);

    }

    public function data(Request $request)
    {
        $province = $request->param('province');
        $county = $request->param('county');
        $currentTime = date('Y-m-d H:i:s');
        $oneMinuteAgo = date('Y-m-d H:i:s', strtotime('-1 minute'));

        $temp = Db::table('citydata')
            ->where('province', $province)
            ->where('county', $county)
            ->whereBetween('time', [$oneMinuteAgo, $currentTime])
            ->select();
        echoJson(1,'',$temp);
        $data = [
            'time'        => $temp[0]['time'],
            'temperature' => $temp[0]['temperature'],
            'humidity'    => $temp[0]['humidity'],
            'pm25'       => $temp[0]['pm25'],
            'pm10'        => $temp[0]['pm10'],
            'Co'          => $temp[0]['Co'],
            'Co2'         => $temp[0]['Co2'],
            'aqi'         => $temp[0]['aqi'],
            'api'         => $temp[0]['api']

        ];
        
        echoJson(1,'查询成功',$data);
    }

    //
    public function get24datac(Request $request)
    {
        $this->info = $_GET;
        $province = $request->param('province');
        $county = $request->param('county');
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
        for($i=1;$i<=72;$i++){
            $temp=$time-3600;
            $time1=date('Y-m-d H:i:s',$time);
            $time2=date('Y-m-d H:i:s',$temp);
            $reu=Db::table('citydata')
                ->where('province', $province)
                ->where('county', $county)
                ->whereBetween('time',[$time2,$time1])
                ->avg($field1);
            $datatemp=[
                'time'=>$time1,
                $field=>$reu
            ];
            $data[]=$datatemp;
            $time=$temp;
        }
        echoJson(1,'查询成功',$data);
    }
}