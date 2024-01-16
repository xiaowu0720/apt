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
////删除用户
//Route::delete('user/deluser','user/Deluser/deluser')->middleware(['Auth']);
////设置用户权限
//Route::put('user/setuser','user/Setuserpermissions/setuserpermissions')->middleware(['Auth']);
////更新用户状态
//Route::put('user/updateuser','user/Updateuser/updateuser')->middleware(['Auth']);
////用户列表
//Route::get('user/userlist','user/Userlist/userlist')->middleware(['Auth']);
////指定用户
//Route::get('user/read', 'user/Userlist/read')->middleware(['Auth']);
//绑定设备
Route::put('bind','equipment/Devicemg/bindequipent')->middleware(['Auth']);
//禁用设备
Route::put('disable','equipment/Devicemg/disablequipment')->middleware(['Auth']);

Route::get('oss', 'aliyun/Getsignature/getOssSignature')->middleware(['Auth']);



//app商店列表
Route::get('liststore','store/Store/liststore')->middleware(['UserLoginAuth']);
Route::get('ranking', 'data/Data/ranking');
//获取验证码
Route::get('getcode','user/Getcode/getcode');
//用户登录
Route::post('user/login','user/Login/user_Login');
//获取实时数据
Route::get('getdata/:id','data/Data/getdata')->middleware(['UserLoginAuth']);
//app获得当日弹窗
Route::get('getpopups','popups/Popups/getpopups')->middleware(['UserLoginAuth']);
//app历史弹窗
Route::get('history','popups/Popups/history')->middleware(['UserLoginAuth']);
//app城市列表
Route::get('citylist','city/City/citylist')->middleware(['UserLoginAuth']);
//日历数据
Route::get('calendardata/:id','data/Data/calendardata')->middleware(['UserLoginAuth']);
//月度排名
Route::get('ranking', 'data/Monthly/data')->middleware(['UserLoginAuth']);
//用户信息
Route::get('infor', 'user/usermessage/usermessage')->middleware(['UserLoginAuth']);
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
//首页展示列表
Route::get('syzslist','store/Store/listsyzslbt')->middleware(['UserLoginAuth']);

//商品详情
Route::get('text', 'store/Store/text')->middleware(['UserLoginAuth']);


return [

];
