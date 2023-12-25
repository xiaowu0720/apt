<?php

namespace app\store\controller;

use app\store\model\StoreModel;
use think\App;
use think\Controller;
use think\Db;
use think\Request;

class Store extends Controller
{
    public $store;
    public function __construct(App $app = null)
    {
        $this->store = new StoreModel();
        parent::__construct($app);
    }

    //商店列表
    public function liststore(Request $request){
        $adname = $request->param('name', null);

        $this->store->liststore($adname);
    }
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index(Request $request)
    {
        $addesc = $request->param('desc', null);
        $phone = $request->param('phone', null);
        $adname = $request->param('name', null);
        $sfsyzs = $request->param('sfsyzs', null);
        $this->store->index($addesc, $phone, $adname, $sfsyzs);
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        $data = [
            'addesc' =>$request->param('addesc'),//商品简介
            'phone'  =>$request->param('phone'),//联系方式
            'money'  =>$request->param('money'),//商品价格
            'image'  =>$request->param('image'),//图片地址
            'adname' =>$request->param('adname'),//商品名称
            'text'   =>$request->param('text'),
            'cerate' =>date('Y-m-d H:i:s',time()),//添加时间
            'state'  =>'1'//软删除
        ];

        $validate = new \app\store\validate\Storevali;
        $validate=new \app\store\validate\Storevali;
        if (!$validate->check($data))
        {
            echoJson(0,$validate->getError());
        }

        $this->store->addstore($data);
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        $this->store->text($id);
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

        $addesc = $request->param('addesc');
        $phone = $request->param('phone');
        $money = $request->param('money');
        $image = $request->param('image');
        $adname = $request->param('adname');
        $data=[];

        if(!empty($addesc)){
            $data['addesc']=$addesc;
        }

        if(!empty($phone)){
            $data['phone']=$phone;
        }

        if(!empty($money)){
            $data['money']=$money;
        }

        if(!empty($image)){
            $data['image']=$image;
        }

        if(!empty($adname)){
            $data['adname']=$adname;
        }

        $this->store->updatestore($id, $data);
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        $this->store->delstore($id);
    }

    //首页展示列表
    public function listsyzslbt(){
        $data=Db::table('mainpage_ad')->select();
        echoJson(1,'查询成功',$data);
    }

    //设置首页展示广告
    public function setsyzslbt(){
        $this->info=$_REQUEST;
        $id=$this->info['id'];
        $this->store->setsyzsad($id);
    }

//    public function text(Request $request)
//    {
//        $id = $request->param('id');
//        $this->store->text($id);
//    }
}
