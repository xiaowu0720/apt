<?php

namespace app\image\controller;

use app\image\validate\ImageV;
use think\Controller;
use think\Db;
use think\Request;

class Image extends Controller
{
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

        $data = Db::table('image')->page($page, $count)->select();

        echoJson(1, '查询成功', $data, $page, sizeof($data));
    }
    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        $image = $request->param('image');
        $data = [
            'image' => $image,
        ];
        $validate = new \app\image\validate\ImageV;
        if (!$validate->check($data)){
            echoJson(0, $validate->getError());
        }
        Db::table('image')->insert($data);
        echoJson(1,'新增成功');
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        $data = Db::table('image')->where('id', $id)->select();
        echoJson(1,'查询成功', $data);
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
        $image = $request->param('image');
        $validate = new ImageV();
        $data = [
            'image' => $image,
        ];
        if (!$validate->check($data)){
            echoJson(0, $validate->getError());
        }
        Db::table('image')->where('id', $id)->update($data);
        echoJson(1,'更新成功');
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        Db::table('image')->where('id', $id)->delete();
        echoJson(1,'删除成功');
    }
}
