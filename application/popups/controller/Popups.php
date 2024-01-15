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
//        $action = $request->param('action', );
        $page = $request->param('page',1);
        $count = $request->param('count',10);

        if (empty($page)) {
            $page = 1;
        }
        if (empty($count)) {
            $count = 10;
        }

        $this->popups->popindex($page, $count);
    }


    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        $start_date = $request->param('start_date', null);
        $end_date = $request->param('end_date', null);
        $content = $request->param('content', null);
        $title = $request->param('title', null);
        $image = $request->param('image', null);

        // 检查开始时间是否小于结束时间
        if (!empty($start_date) && !empty($end_date)) {
            $startDateTime = new DateTime($start_date);
            $endDateTime = new DateTime($end_date);

            if ($startDateTime >= $endDateTime) {
                echoJson(0, '开始时间必须小于结束时间');
                return;
            }
        }

        $data = [
            'start_date' => $start_date,
            'end_date' => $end_date,
            'content' => $content,
            'title' => $title,
            'image' => $image
        ];

        $validate = new Popupsv();
        if (!$validate->check($data)) {
            echoJson(0, $validate->getError());
            return;
        }

        $this->popups->addpop($data);
        // 如果以上条件都满足，执行保存逻辑
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
            $data = Db::table('send_message')
                ->where('id', $id)
                ->select();

            echoJson(1,'查询成功',$data,1,1);
        }
        $year = $request->param('year',null);
        $month = $request->param('month',null);
        $day = $request->param('day',null);
        $title = $request->param('title',null);
        $page = $request->param('page',1);
        $count = $request->param('count',10);


        $data = Db::table('send_message');
        $temp = Db::table('send_message');

        if (!empty($year)) {
            $data = $data->where('start_date','like',$year.'%')
                ->whereOr('end_date','like',$year.'%');
            $temp = $data->where('start_date','like',$year.'%')
                ->whereOr('end_date','like',$year.'%');
        }

        if (!empty($month)) {
            if ($month) {
                $month = str_pad($month, 2, '0', STR_PAD_LEFT);

            }
            $data = $data->where('start_date','like','_____'.$month.'___')
                ->whereOr('end_date','like','_____'.$month.'___');
            $temp = $data->where('start_date','like','_____'.$month.'___')
                ->whereOr('end_date','like','_____'.$month.'___');
        }

        if (!empty($day)) {
            if ($day) {
                $day = str_pad($day, 2,'0',STR_PAD_LEFT);
            }
            $data = $data->where('start_date','like','________'.$day)
                ->whereOr('end_date','like','________'.$day);
            $temp = $data->where('start_date','like','________'.$day)
                ->whereOr('end_date','like','________'.$day);
        }

        if (!empty($title)) {
            $data = $data->where('title','like','%'.$title.'%');
            $temp = $data->where('title','like','%'.$title.'%');

        }

        $data = $data
            ->page($page,$count)
            ->select();

        $temp = $temp
            ->select();

        echoJson(1,'查询成功',$data, $page,count($temp));

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

        $currentDate = new DateTime();
        $currentDate->setTime(0, 0, 0);

        $date = $request->param('end_date', null);

        if (!empty($date)) {
            $givenDate = new DateTime($date);
            $givenDate->setTime(0, 0, 0);

            if ($givenDate < $currentDate) {
                echoJson(0, '更新的时间必须大于等于当前时间');
            }

            $data['end_date'] = $date;
        }


        $content = $request->param('content', null);

        if(!empty($content)) {
            $data['content'] = $content;
        }

        $title = $request->param('title', null);

        if (!empty($title)) {
            $data['title'] = $title;
        }

        $image = $request->param('image', null);

        if (!empty($image)) {
            $data['image'] = $image;
        }

        Db::table('send_message')->where('id', $id)->update($data);
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
        Db::table('send_message')->where('id',$id)->delete();
        Db::table('send_message')->where('id', '>', $id)->setDec('id');
        echoJson(1,'删除成功');
    }

    public function history(Request $request)
    {
        $date = $request->param('date', null);
        $title = $request->param('title',null);
        $currentDate = date('Y-m-d'); // 获取当前日期

        $data = Db::table('send_message')
            ->where('start_date','<=',$currentDate);


        if (!empty($date)) {
            $data = $data->whereColumn($date, '>=', 'start_date')
                ->whereColumn($date, '<=', 'end_date');;
        }


        if (!empty($title)) {
            $data = $data->where('title','like','%'.$title.'%');
        }

        $data = $data->select();

        echoJson(1,'查询成功',$data);
    }
}
