<?php
namespace app\announcement\model;

use think\Db;
use think\Model;

class Announcementm extends Model{

    //添加新的公告
    public function addannouncement($id,$minaqi,$maxaqi,$content){
        $data=[
            'id'=>$id,
            'minaqi'=>$minaqi,
            'maxaqi'=>$maxaqi,
            'content'=>$content
        ];
        if ($maxaqi <= $minaqi) {
            echoJson(0,'max必须小于min');
        }

        $reult=Db::table('announcement')->where('minaqi','<=',$maxaqi)->where('maxaqi','>=',$minaqi)->select();
        if(!empty($reult)){
            echoJson(0,'已经存在这个范围的公告');
        }
        Db::table('announcement')->insert($data);
        echoJson(1,'添加成功');
    }
    //更新公告
    public function updateannouncement($id,$minaqi,$maxaqi,$content){
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
        Db::table('announcement')->where('id',$id)->update($data);
        echoJson(1,'更新成功');
    }
}