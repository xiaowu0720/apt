<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

function echoJson($code,$msg,$result=[],$page=null,$count=10)
{
    header('Content-Type:application/json; charset=utf-8');
    if($page == null)
    {
        $arr = array("code"=>$code,"msg"=>$msg,"result"=>$result);
    }else{
        $resultArr ["data"]= $result;
        $resultArr["page"]   = $page;
        $resultArr["count"]  = $count;
        $arr = array("code"  => $code, "msg" => $msg, "result" => $resultArr);
    }
    echo json_encode($arr, JSON_UNESCAPED_UNICODE);
    exit;
}

function init_redis()
{
    $redis = new Redis();
    $redis->connect('47.100.110.213',6379);
    $redis->auth("Mebay20190121");
    $redis->select(8);
    return $redis;
}

//当前时间的前24小时
function time24($time)
{
    $time = $time-86400;
    return $time;
}

//当前时间前72小时
function time72($time)
{
    $time = $time - 72 * 3600;
    return $time;
}

//获取当前日期的数据表名称
function getTableName()
{
    $currentTime = time();
    setlocale(LC_TIME, 'zh_CN.utf8');
    $tablename = strftime('%Y_%m', $currentTime);
    return $tablename;
}

function object_array($array)
{
    if(is_object($array)) {
        $array = (array)$array;
    }
    if(is_array($array)) {
        foreach($array as $key => $value) {
            $array[$key] = $value;
        }
    }
    return $array;
}

//计算两点距离
function distance($x1, $y1, $x2, $y2)
{
    $distance = sqrt(pow(($x2 - $x1), 2) + pow(($y2 - $y1), 2));
    return $distance;
}


function generateCode($length) {
    $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $code = '';
    for ($i = 0; $i < $length; $i++) {
        $code .= $chars[rand(0, strlen($chars) - 1)];
    }
    return $code;
}


function getsitename($id)
{
    $data = \think\Db::table('site')
        ->where('id', $id)
        ->select();
    return $data[0]['name'];

}

function getdevice_address($id)
{
    $data = \think\Db::table('equipment')
        ->where('sid', $id)
        ->select();
    return $data[0]['device_address'];
}

