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

/**
 * 邮箱验证码
 * @param string $to 发送到邮箱
 * @param string $name 当前邮箱服务器
 * @return string $subject 发送标题
 * @return string $body 发送内容
 * @return string $attachment 附件
 */
function send_mail($to, $name, $subject = '', $body = '',$attachment = null) {
    $mail = new \PHPMailer\PHPMailer\PHPMailer();           //实例化PHPMailer对象
    $mail->CharSet = 'UTF-8';           //设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置，否则乱码
    $mail->IsSMTP();                    // 设定使用SMTP服务
    $mail->SMTPDebug = 1;               // SMTP调试功能 0=关闭 1 = 错误和消息 2 = 消息
    $mail->SMTPAuth = true;             // 启用 SMTP 验证功能
    $mail->SMTPSecure = 'ssl';          // 使用安全协议
    $mail->Host = "smtp.gmail.com";       // SMTP 服务器
    $mail->Port = 465;                  // SMTP服务器的端口号
    $mail->Username = 'xiaowutongxue1234@gmail.com';    // SMTP服务器用户名
    $mail->Password = 'wqc040720.';     // SMTP服务器密码//这里的密码可以是邮箱登录密码也可以是SMTP服务器密码
    $mail->SetFrom('发件邮箱', 'xxx有限公司');
    $replyEmail = '环境监测';                   //留空则为发件人EMAIL
    $replyName = '';                    //回复名称（留空则为发件人名称）
    $mail->AddReplyTo($replyEmail, $replyName);
    $mail->Subject = $subject;
    $mail->MsgHTML($body);
    $mail->AddAddress($to, $name);
    if (is_array($attachment)) { // 添加附件
        foreach ($attachment as $file) {
            is_file($file) && $mail->AddAttachment($file);
        }
    }
    return $mail->Send() ? true : $mail->ErrorInfo;
}

function generateRandomCode($length = 6) {
    $characters = '0123456789';
    $code = '';

    for ($i = 0; $i < $length; $i++) {
        $code .= $characters[rand(0, strlen($characters) - 1)];
    }

    return $code;
}

