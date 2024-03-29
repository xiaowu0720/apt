<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

Route::get('think', function () {
    return 'hello,ThinkPHP5!';
});

Route::get('hello/:name', 'index/hello');

//管理员登录
Route::post('user/loginadmin','user/Loginadmain/loginadmain');

Route::resource('store', 'store/Store')->middleware(['Auth']);
Route::resource('site', 'site/Site')->middleware(['Auth']);
Route::resource('popups', 'popups/Popups')->middleware(['Auth']);
Route::resource('equipment', 'equipment/Devicemg')->middleware(['Auth']);
Route::resource('city', 'city/City');
Route::resource('announcement', 'announcement/Announcement')->middleware(['Auth']);
Route::resource('user','user/User')->middleware(['Auth']);
Route::resource('image','image/Image')->middleware(['Auth']);
Route::resource('send_message','popups/Popups')->middleware(['Auth']);

Route::get('oss', 'aliyun/Getsignature/getOssSignature')->middleware(['Auth']);

//站点目录带数据
Route::get('sitelist','site/Site/sitelist');
Route::get('ranking', 'data/Data/ranking');
//获取验证码
Route::post('email','user/Index/getEmailCode');
//用户登录
Route::post('user/login','user/Login/user_Login');
//用户注册
Route::post('enroll', 'user/Enroll/user_enroll');
//获取实时数据
Route::get('getdata/:id','data/Data/getdata')->middleware(['UserLoginAuth']);
//日历数据
Route::get('calendardata/:id','data/Data/calendardata')->middleware(['UserLoginAuth']);
//站点列表
Route::get('sitelist','collection/Collection/sitelist')->middleware(['UserLoginAuth']);
//收藏得实时数据
Route::get('data','collection/Collection/data')->middleware(['UserLoginAuth']);
//删除收藏
Route::delete('del','collection/Collection/del')->middleware(['UserLoginAuth']);
//添加收藏
Route::put('add','collection/Collection/add')->middleware(['UserLoginAuth']);
//收藏列表
Route::get('list','collection/Collection/colist')->middleware(['UserLoginAuth']);
//获取最近站点
Route::get('location','data/Data/location')->middleware(['UserLoginAuth']);
//月度分析
Route::get('mothdata/:id','data/Data/mothdata');
//年度分析
Route::get('yeardata/:id', 'data/Data/yeardata');
//得到今日弹窗
Route::get('today/:id', 'popups/day/day');
//得到今日公告
Route::get('day/:id', 'announcement/day/day');
//忘记密码
Route::post('forget', 'user/Forget/forget');
//商品详情
Route::get('text', 'store/Store/text')->middleware(['UserLoginAuth']);


return [

];
