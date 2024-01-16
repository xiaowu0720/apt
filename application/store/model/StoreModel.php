<?php


namespace app\store\model;


use app\common\model\Jwt_base;
use app\data\controller\Data;
use think\Db;
use think\Model;

class StoreModel extends Model
{
    public $jwt;
    public function __construct($data = [])
    {
        $this->jwt = new Jwt_base();
        parent::__construct($data);
    }

    public function liststore($adname)
    {
        $this->jwt->check();
        $query = Db::table('ad')
            ->where('state', 1);

        if (!empty($adname)) {
            $query = $query->where('adname', 'like', '%' . $adname . '%');
        }

        $data1 = $query->select();
        $data = [];
        foreach ($data1 as $temp) {
            $temp1 = [
                'id'    => $temp['id'],
                'name'  => $temp['adname'],
                'desc'  => $temp['addesc'],
                'money' => $temp['money'],
                'phone' => $temp['phone'],
                'image' => $temp['image'],
            ];
            $data[] = $temp1;
        }
        echoJson(1, '查询成功', $data);
    }

    public function index($page, $count)
    {
        $data = Db::table('ad');

        $data = $data
            ->page($page,$count)
            ->select();

        $count = count(Db::table('ad')->select());
        echoJson(1,'查询成功',$data, $page, $count);
    }

    public function addstore($data)
    {
        Db::table('ad')->insert($data);
        echoJson(1,'添加成功');
    }

    public function delstore($id)
    {
        $data=[
            'state'   => 0,
            'deltime' => date('Y-m-d H:i:s',time()),
        ];
        Db::table('ad')->where('id',$id)->update($data);
        echoJson(1,'删除成功');
    }

    public function updatestore($id, $data)
    {
        Db::table('ad')
            ->where('id', $id)
            ->update($data);

        echoJson(1,'更新成功');
    }


    public function text($id)
    {
        $data = Db::table('ad')
            ->where('id',$id)
            ->field('id,text,adname,addesc,money,phone,image')
            ->select();
        if (empty($data)) {
            echoJson(0,'商品不存在');
        }

        echoJson(1, '查询成功', $data[0]);
    }
}