<?php

namespace app\announcement\controller;

use app\announcement\model\Announcementm;
use think\App;
use think\Controller;
use think\Db;
use think\Request;

class Announcement extends Controller
{
    public $anno;
    public function __construct(App $app = null)
    {
        $this->anno = new Announcementm();
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
        $data=Db::table('announcement')
            ->page($page, $count)
            ->order('minaqi','asc')
            ->select();
        $count = Db::table('announcement')->count();

        echoJson(1,'查询成功',$data,$page,$count);
    }


    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        $id=Db::table('announcement')->count()+1;
        $minaqi = $request->param('minaqi');
        $maxaqi = $request->param('maxaqi');
        $content = $request->param('content');
        $this->anno->addannouncement($id,$minaqi,$maxaqi,$content);
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        //
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
        $minaqi = $request->put('minaqi');
        $maxaqi = $request->put('maxaqi');
        $content = $request->put('content');

//        $this->info=$_REQUEST;
//        $minaqi=$this->info['minaqi'];
//        $maxaqi=$this->info['maxaqi'];
//        $content=$this->info['content'];
        $this->anno->updateannouncement($id,$minaqi,$maxaqi,$content);
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        Db::table('announcement')->where('id',$id)->delete();
        echoJson(1,'删除成功');
    }
}
