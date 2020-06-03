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

/**
 * 小程序API
 */
Route::middleware('cors')->prefix('wechat')->group(function () {
    Route::get('index', 'WeChat\IndexController@index');
    Route::get('login', 'WeChat\IndexController@login');
    Route::get('getJssdk', 'WeChat\IndexController@getJssdk');
    Route::post('sendSms', 'WeChat\SmsController@sendSms'); // 发送短信验证码
    Route::get('judgeSms', 'WeChat\SmsController@judgeSms'); // 验证电话验证码
    Route::get('draw', 'WeChat\DrawController@draw');
    Route::get('initialization', 'WeChat\DrawController@initialization');
    Route::get('winRecord', 'WeChat\RaffleController@winRecord');//我的中奖纪录
    Route::get('isHavePhone', 'WeChat\RaffleController@isHavePhone');//是否有电话
    Route::post('repost', 'WeChat\RaffleController@repost');//转发
    Route::get('winnerUsers', 'WeChat\RaffleController@winnerUsers');//滚动中奖名单列表
    Route::get('raffleActivityDetail', 'WeChat\RaffleController@raffleActivityDetail');//滚动中奖名单列表
});

/**
 * 后台API
 */
Route::middleware('cors')->prefix('admin')->group(function () {

    Route::middleware(['auth:api'])->group(function () {
        Route::get('user', 'Admin\UserController@index'); // 用户信息
        Route::resource('raffleUrlCode', 'Admin\RaffleUrlCodeController'); // 批量生成二维码
        Route::get('distributor', 'Admin\RaffleDistributorController@index'); // 经销商奖金分配
        Route::resource('raffleStates', 'Admin\RaffleStateController')->only(['index', 'store']); // 奖金池管理
        Route::post('changeIsGrand', 'Admin\RaffleStateController@changeIsGrand'); // 修改是否为大奖
        Route::get('raffleActivity', 'Admin\RaffleStateController@raffleActivity');// 抽奖活动规则
        Route::post('updateRaffleActivity', 'Admin\RaffleStateController@updateRaffleActivity');//更改抽奖活动规则
        Route::get('raffleDrawList', 'Admin\RaffleDrawController@raffleDrawList');//中奖名单
    });
});


