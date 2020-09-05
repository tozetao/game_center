<?php
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

use Illuminate\Support\Facades\Route;

if (env('APP_DEBUG')) {
    // demo接口
    Route::get('/demo', 'SessionController@demo');
    Route::get('/test/login', 'SessionController@test')->middleware('startApiSession');
}

// goods
Route::get('/goods', 'GoodsController@search');
Route::get('/goods/{id}', 'GoodsController@show');
Route::get('/goods_total', 'GoodsController@total');

// category
Route::get('/categories', 'CategoryController@index');

// 省市区
Route::get('/provinces', 'AreaController@province');
Route::get('/cities', 'AreaController@city');
Route::get('/areas', 'AreaController@area');

// 登陆接口
Route::post('/login', 'SessionController@login')->middleware('startApiSession');

Route::middleware(['startApiSession', 'auth.api'])->group(function() {
    Route::get('/users/info', 'UserController@info');

    // 玩家地址
    Route::post('/addrs', 'UserAddrController@create');
    Route::put('/addrs/{id}', 'UserAddrController@edit');
    Route::get('/addrs', 'UserAddrController@index');

    // 商品订单
    Route::post('/orders', 'OrderController@createGoodsOrder');
    Route::post('/orders/pay', 'OrderController@pay');  // 进行支付
    Route::get('/orders', 'OrderController@search');    // 搜索订单
    Route::get('/orders/{no}/status', 'OrderController@status');    // 订单支付状态

    // 道具订单
    Route::post('/prop_orders', 'PropOrderController@store');
    Route::post('/prop_orders/pay', 'PropOrderController@pay');
    Route::get('/prop_orders/{no}/status', 'PropOrderController@status');
    Route::get('/myprops', 'MyPropController@index');   // 查询我的道具

    // 查询玩家购买道具
    Route::get('/user/props', 'UserController@props');

    // 存档相关
    Route::post('/archives', 'ArchiveController@create');
    Route::get('/archives', 'ArchiveController@index');
    Route::delete('/archives/{id}', 'ArchiveController@delete');

    // 设置
    Route::post('/setting/base', 'UserSettingController@baseSetting');  // 基本设置
    Route::post('/setting/icons', 'UserSettingController@iconSetting'); // 表情设置
    Route::post('/setting/checkpoint', 'UserSettingController@checkpoint'); // 关卡设置

    // 查询存档相关设置
    Route::get('/setting/mine', 'UserSettingController@mySetting');
    Route::get('/setting/suitor', 'UserSettingController@suitor');

    // 关联
    Route::post('/relation', 'UserSettingController@related');
    Route::get('/relation', 'UserSettingController@relation');

    // 查询我的复活次数
    Route::get('/resurrections', 'UserSettingController@resurrections');

    // 增加我追求的人的复活次数
    Route::post('/resurrections', 'UserSettingController@resurrection');

    // 申请复活
    Route::post('/resurrections/apply', 'ResurrectionController@apply');

    // 查询申请复活的操作状态
    Route::get('/resurrections/apply/status', 'ResurrectionController@status');

    // 上传图片
    Route::post('/upload/image', 'FileController@uploadImage');

    // 红包接口
    Route::post('/red_envelopes', 'RedEnvelopeController@store');
    Route::post('/red_envelopes/pay', 'RedEnvelopeController@pay');
    Route::post('/red_envelopes/send', 'RedEnvelopeController@send');
    Route::get('/red_envelopes/{no}/status', 'RedEnvelopeController@status');   // 红包支付状态
});

// 微信支付回调
Route::post('/notify/wechat/jsapi', 'WechatController@notify')->name('notify.wechat.jsapi');