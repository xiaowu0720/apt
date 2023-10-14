<?php

namespace app\site\controller;

use app\common\model\Jwt_base;
use app\site\model\SiteModel;
use app\site\validate\Sitev;
use think\App;
use think\Controller;
use think\Db;
use think\Request;

class Site extends Controller
{
    public $site;
    public function __construct(App $app = null)
    {
        $this->site = new SiteModel();
        parent::__construct($app);
    }
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index(Request $request)
    {
        $page = $request->param('page',1);
        $count = $request->param('count',10);

        if (empty($page)) {
            $page = 1;
        }
        if (empty($count)) {
            $count = 10;
        }

        $this->site->siteindex($page, $count);
    }


    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        $time = date('Y-m-d H:i:s',time());
        $data = [
            'id'        => Db::table('site')->count() + 1,
            'name'      => $request->param('name'),
            'longitude' => $request->param('longitude'),
            'latitude'  => $request->param('latitude'),
            'address'   => $request->param('address'),
            'province'  => $request->param('province'),
            'county'    => $request->param('county'),
            'time'      => $time
        ];

        $validate = new Sitev();
        if(! $validate->check($data)){
            echoJson(0,$validate->getError());
        }
        $this->site->sitesave($data);
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
            $data = Db::table('site')
                ->where('id', $id)
                ->select();

            echoJson(1,'查询成功',$data,1,1);
        }
        $name = $request->param('name',null);
        $province = $request->param('province',null);
        $county = $request->param('county',null);
        $address = $request->param('address',null);
        $page = $request->param('page', 1);
        $count = $request->param('count', 10);

        if (empty($page)) {
            $page = 1;
        }
        if(empty($count)){
            $count = 10;
        }

        $this->site->read($name, $province, $county, $address,$page,$count);
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
        $name = $_REQUEST['name'];
        $longitude = $_REQUEST['longitude'];
        $latitude = $_REQUEST['latitude'];
        $address = $_REQUEST['address'];
        $province = $_REQUEST['province'];
        $county = $_REQUEST['county'];

        $data = [];

        if (!empty($name)) {
            $data['name'] = $name;
        }

        if (!empty($longitude)) {
            $data['longitude'] = $longitude;
        }

        if (!empty($latitude)) {
            $data['latitude'] = $latitude;
        }

        if (!empty($address)) {
            $data['address'] = $address;
        }

        if (!empty($province)) {
            $data['province'] = $province;
        }

        if (!empty($county)) {
            $data['county'] = $county;
        }

        $this->site->siteupdate($id,$data);
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        Db::table('site')->where('id', $id)->delete();

        // 更新大于当前ID的记录
//        Db::table('site')->where('id', '>', $id)->setDec('id');

        echoJson(1, '删除成功');
    }
}
