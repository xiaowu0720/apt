<?php

namespace app\city\controller;

use app\city\model\Citymodel;
use think\App;
use think\Controller;
use think\Db;
use think\Request;

class City extends Controller
{
    public $city;
    public function __construct(App $app = null)
    {
        $this->city = new Citymodel();
        parent::__construct($app);
    }

    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index(Request $request)
    {
        $page = $request->param('page', 1);
        $count = $request->param('count', 10);
        if (empty($page)) {
            $page = 1;
        }

        if (empty($count)) {
            $count = 10;
        }
        $data = Db::table('citylist')
            ->order('province desc')
            ->page($page, $count)
            ->select();
        $count = Db::table('citylist')->select();

        echoJson(1, '查询成功', $data, $page, count($count));
    }


    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        $info=$_POST;
        $data=[
            'id'=>Db::table('citylist')->count()+1,
            'cityname'=>$info['cityname'],//城市名称
            'date'=>date('Y-m-d H.i.s',time()),
            'province'=>$info['province'],//所属省
        ];
        $this->city->addcity($data);
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id, Request $request)
    {
        $id = strtolower($id);
        if($id != 'null') {
            $data = Db::table('citylist')
                ->where('id', $id)
                ->select();

            echoJson(1,'查询成功',$data,1,1);
        }
        $cityname = $request->param('cityname', null);
        $province = $request->param('province',null);
        $page = $request->param('page', 1);
        $count = $request->param('count', 10);
        if (empty($page)) {
            $page = 1;
        }

        if (empty($count)) {
            $count = 10;
        }

        $this->city->readcity($cityname, $province, $page, $count);
    }


    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        $cityname = $request->param('cityname',null);
        $province = $request->param('province',null);
        $this->city->revisecity($id,$cityname,$province);
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        $this->city->delcity($id);
    }

    public function citylist()
    {
        $this->info=$_GET;
        $temp = Db::table('citylist')
            ->order('province desc')
            ->field('province')
            ->distinct(true)
            ->select();
        $data = [];
        foreach ($temp as $temp1) {
            $temp2 = Db::table('citylist')
                ->where('province', $temp1['province'])
                ->field('cityname')
                ->select();
            $temp3 = [
                'province' => $temp1['province'],
                'city'     => $temp2
            ];
            $data[] = $temp3;
        }
        echoJson(1,'查询成功',$data);
    }
}
