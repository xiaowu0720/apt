<?php

namespace app\equipment\controller;

use app\equipment\model\Equipment;
use think\App;
use think\Controller;
use think\Db;
use think\Request;

class Devicemg extends Controller
{
    public $equip;
    public function __construct(App $app = null)
    {
        $this->equip = new Equipment();
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

        $data = Db::table('equipment')->page($page, $count)->select();

        echoJson(1,'查询成功',$data,$page,count($data));
    }


    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        $device_address = $request->param('device_address');
        $equipmentname = $request->param('equipmentname');
        $site = $request->param('site', null);
        $this->equip->addequipment($device_address,$equipmentname, $site);
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
            $data = Db::table('equipment')
                ->where('id', $id)
                ->select();

            echoJson(1,'查询成功',$data,1,1);
        }
        $state = $request->param('state', null);
        $equipmentname = $request->param('equipmentname', null);
        $device_address = $request->param('device_address', null);
        $site = $request->param('site', null);
        $page = $request->param('page', 1);
        $count = $request->param('count', 10);
        $this->equip->listequipment($state, $device_address, $equipmentname, $site, $page, $count);
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
        $equipmentname = $request->param('equipmentname', null);
        $device_address = $request->param('device_address', null);
        $site = $request->param('site', null);
        $state = $request->param('state', null);

        $this->equip->updateequ($id,$equipmentname, $device_address, $site, $state);
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        // 删除设备
        Db::table('equipment')->where('id', $id)->delete();

        // 将大于删除设备id的数减一
        Db::table('equipment')->where('id', '>', $id)->setDec('id');

        echoJson(1,'删除成功');
    }

//    public function bindequipent(Request $request){
//        $id = $request->param('id');
//        $site = $request->param('site');
//        $this->equip->bindequipent($id,$site);
//    }
//
//    public function disablequipment(){
//        $this->info=$_REQUEST;
//        $id=$this->info['id'];
//        $state=$this->info['state'];
//        $data=[
//            'state'=>$state
//        ];
//        Db::table('equipment')->where('id',$id)->update($data);
//        echoJson(1,'更新成功');
//    }
}
