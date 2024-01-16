<?php

namespace app\popups\controller;

use app\popups\model\Popupsmodel;
use app\popups\validate\Popupsv;
use think\App;
use think\Controller;
use think\Db;
use think\Request;
use DateTime;

class Popups extends Controller
{
    public $popups;

    public function __construct(App $app = null)
    {
        $this->popups = new Popupsmodel();
        parent::__construct($app);
    }
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index(Request $request)
    {
        $action = $request->param('action', 'setup');
        $page = $request->param('page',1);
        $count = $request->param('count',10);

        if (empty($page)) {
            $page = 1;
        }
        if (empty($count)) {
            $count = 10;
        }

        $this->popups->popindex($page, $count, $action);
    }


    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {

    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id, Request $request)
    {
        $data = Db::table('popup')
            ->where('id', $id)
            ->select();

        echoJson(1,'查询成功',$data,1,1);
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

    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        Db::table('popup')->where('id',$id)->delete();
        echoJson(1,'删除成功');
    }

}
