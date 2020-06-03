<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/
//管理后台相关
Route::namespace('Admin')->group(function () {
    //登录
    Route::post('admin/login', 'LoginController@login');
    //管理员
    //TODO 需要增加权限时，增加中间件：middleware('role.permission')->
    Route::middleware('jwt:admin')->group(function () {
        /***管理员***/
        Route::namespace('Administrator')->group(function () {
            //修改其他管理员
            Route::post('admin/update-other', 'AdministratorController@updateOther');
            //修改自身信息
            Route::post('admin/update-self', 'AdministratorController@updateSelf');
            //获取某管理员的角色权限
            Route::get('admin/get-role-by-admin-id', 'AdministratorController@getRoleByAdminId');
            //获取当前管理员的角色权限
            Route::get('admin/get-role-by-self', 'AdministratorController@getRoleBySelf');
            //修改自己密码
            Route::post('admin/update-self-pass', 'AdministratorController@updateSelfPass');
            //管理员相关基础功能
            Route::resource('admin/administrator', 'AdministratorController');
        });
        /***角色 - 权限***/
        Route::namespace('RolePermission')->group(function () {
            //赋予当前管理员执行某个角色
            Route::post('admin/administrator-role/{role}', 'RoleController@setAdministratorRole');
            //获取某个角色下面的权限
            Route::get('admin/role-has-permission/{role}', 'RoleController@getPermissionByRole');
            //角色相关基础功能
            Route::resource('admin/role', 'RoleController');
            //权限相关基础功能
            Route::resource('admin/permission', 'PermissionController');
        });
        /*** ElasticSearch ***/
        Route::namespace('ElasticSearch')->group(function () {
            Route::post('admin/elastic/index', 'ElasticController@createIndex');
            Route::post('admin/elastic/mappings', 'ElasticController@createMappings');
            Route::post('admin/elastic/create-data', 'ElasticController@createData');
        });
    });
});


//微信公众号相关
Route::namespace('Api')->group(function () {
    Route::any('/wechat', 'WechatOfficialAccountController@serve');
});

//workerMan
Route::namespace('Api')->group(function () {
    //绑定
    Route::post('workerman/build', 'ChatController@build');
    //发送消息
    Route::post('workerman/send-message', 'ChatController@sendMessage');
    //获取未读消息
    Route::get('workerman/get-no-read-message', 'ChatController@getMessage');
});




//相关测试
//敏感词
Route::namespace('test')->group(function () {
    Route::post('admin/sensitive', 'TestController@sensitive');
});
