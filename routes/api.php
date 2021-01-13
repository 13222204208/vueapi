<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function (){

    Route::group(['namespace' => 'Admin\Login'], function () {

        Route::post('login','LoginController@login');//登录

        Route::post('logout','LoginController@logout');//登出

        Route::group(['middleware' => 'auth:admin'], function () {
            Route::get('info','LoginController@info');//获取后台登陆信息      
        });
    });

    Route::group(['namespace' => 'Admin\Back'], function () {

        Route::group(['middleware' => 'auth:admin'], function () {   
    
            Route::resource('account', 'AccountController');//后台帐号
            Route::resource('permission', 'PermissionController');//后台权限
            
        });
    });

});
