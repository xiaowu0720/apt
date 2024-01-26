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
        echoJson(1, 'The query succeeded', $data, $page, $count);
    }

    public function addpopup($id,$minaqi,$maxaqi,$content){
        $data=[
            'id'=>$id,
            'minaqi'=>$minaqi,
            'maxaqi'=>$maxaqi,
            'content'=>$content
        ];
        if ($maxaqi <= $minaqi) {
            echoJson(0,'Max must be less than min');
        }

        $reult=Db::table('send_message')->where('minaqi','<=',$maxaqi)->where('maxaqi','>=',$minaqi)->select();
        if(!empty($reult)){
            echoJson(0,'There are already pop-ups for this range');
        }
        Db::table('send_message')->insert($data);
        echoJson(1,'The addition was successful');
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
            echoJson(0,'Max must be less than min');
        }
        Db::table('send_message')->where('id',$id)->update($data);
        echoJson(1,'The update was successful');
    }
}