<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['namespace' => 'Api','prefix' => 'api','middleware'=>'throttle'], function(){
    Route::group(['prefix' => 'yunshi'], function() {
        Route::get('get_wx_params', 'YunshiController@get_wx_params');
        Route::get('get_user_info', 'YunshiController@get_user_info');
        Route::get('download_img', function () {
            echo get_img_source();
        });
    });

    Route::group(['prefix' => 'zitiao'], function() {
        Route::get('get_wx_params', 'ZitiaoController@get_wx_params');
        Route::get('get_user_info', 'ZitiaoController@get_user_info');
        Route::get('get_message', 'ZitiaoController@get_message');
        Route::post('build_message', 'ZitiaoController@build_message');
        Route::post('del_message', 'ZitiaoController@del_message');
    });

    Route::group(['prefix' => 'yuanxiao'], function() {
        Route::get('get_wx_params', 'YuanxiaoController@get_wx_params');
        Route::get('get_user_info', 'YuanxiaoController@get_user_info');
        Route::post('get_header_img', 'YuanxiaoController@get_header_img');
    });

    Route::get('download_img', function () {
        echo get_img_source();
    });

    Route::post('upload_file', 'ApiController@upload_file');
});

