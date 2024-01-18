<?php


namespace app\popups\model;


use think\Db;
use think\Model;

class Popupsmodel extends Model
{
    public function popindex($page, $count, $action)
    {
        if ($action == 'setup') {
            $data = Db::table('send_message')->page($page, $count)->select();
            $count = Db::table('send_message')->count();
        }else {
            $data = Db::table('popup')->page($page, $count)->select();
            $count = Db::table('popup')->count();
        }
        echoJson(1, '查询成功', $data, $page, $count);
    }

    public function addpopup($id,$minaqi,$maxaqi,$content){
        $data=[
            'id'=>$id,
            'minaqi'=>$minaqi,
            'maxaqi'=>$maxaqi,
            'content'=>$content
        ];
        if ($maxaqi <= $minaqi) {
            echoJson(0,'max必须小于min');
        }

        $reult=Db::table('send_message')->where('minaqi','<=',$maxaqi)->where('maxaqi','>=',$minaqi)->select();
        if(!empty($reult)){
            echoJson(0,'已经存在这个范围的弹窗');
        }
        Db::table('send_message')->insert($data);
        echoJson(1,'添加成功');
    }

    public function updatepopup($id,$minaqi,$maxaqi,$content){
        $data=[];
        if(!empty($minaqi)){
            $data['minaqi'] = $minaqi;
        }
        if(!empty($maxaqi)){
            $data['maxaqi'] = $maxaqi;
        }
        if(!empty($content)){
            $data['content'] = $content;
        }
        if (!empty($maxaqi) && !empty($minaqi) && $maxaqi <= $minaqi) {
            echoJson(0,'max必须小于min');
        }
        Db::table('send_message')->where('id',$id)->update($data);
        echoJson(1,'更新成功');
    }
}