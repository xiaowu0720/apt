<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// [ 应用入口文件 ]
namespace think;

header('Content-Type: text/html;charset=utf-8');
header('Access-Control-Allow-Origin:*'); // *代表允许任何网址请求
header('Access-Control-Allow-Methods:*'); // 允许请求的类型
header('Access-Control-Allow-Credentials: true'); // 设置是否允许发送 cookies
header('Access-Control-Allow-Headers:*'); // 设置允许自定义请求头的字段

if($_SERVER['REQUEST_METHOD'] == 'OPTIONS'){
    exit;
}
// 定义应用目录
define('EXTEND_PATH',__DIR__ .'/extend/');
define('APP_PATH', __DIR__ . '/application/');
define('APP_HOOK',true);
define('ROOT_PATH',__DIR__);
// 加载框架引导文件
require __DIR__ . '/thinkphp/base.php';
// 执行应用并响应
Container::get('app', [APP_PATH])->run()->send();