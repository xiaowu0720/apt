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

        echoJson(1,'The query succeeded',$data,$page,count($data));
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
        $sid = $request->param('sid', null);
        $this->equip->addequipment($device_address,$equipmentname, $sid);
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

            echoJson(1,'The query succeeded',$data,1,1);
        }
        $state = $request->param('state', null);
        $equipmentname = $request->param('equipmentname', null);
        $device_address = $request->param('device_address', null);
        $sid = $request->param('sid', null);
        $page = $request->param('page', 1);
        $count = $request->param('count', 10);
        $this->equip->listequipment($state, $device_address, $equipmentname, $sid, $page, $count);
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
        $sid = $request->param('sid', null);
        $state = $request->param('state', null);

        $this->equip->updateequ($id,$equipmentname, $device_address, $sid, $state);
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

        echoJson(1,'The deletion is successful');
    }
}
