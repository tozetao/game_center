<?php

use \Illuminate\Support\Facades\Route;

Route::get('/home', 'HomeController@index');
Route::get('/home/console', 'HomeController@console')->name('console');

Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login.show');
Route::post('/login', 'Auth\LoginController@login')->name('login');
Route::get('/logout', 'Auth\LoginController@logout')->name('logout');

Route::get('/roles', 'RoleController@index')->name('role.index');
Route::get('/roles/{role_id}/edit', 'RoleController@edit')->name('role.edit');
Route::post('/roles/{role_id}', 'RoleController@update')->name('role.update');
Route::get('/roles/create', 'RoleController@create')->name('role.create');
Route::post('/roles', 'RoleController@store')->name('role.store');

Route::get('/administrators', 'AdministratorController@index')->name('admin.index');
Route::get('/administrators/create', 'AdministratorController@create')->name('admin.create');
Route::post('/administrators', 'AdministratorController@store')->name('admin.store');
Route::get('/administrators/{id}/edit', 'AdministratorController@edit')->name('admin.edit');
Route::put('/administrators/{id}', 'AdministratorController@update')->name('admin.update');

Route::get('/goods', 'GoodsController@index')->name('goods.index');
Route::get('/goods/create', 'GoodsController@create')->name('goods.create');
Route::post('/goods', 'GoodsController@store')->name('goods.store');
Route::get('/goods/{goods_id}/edit', 'GoodsController@edit')->name('goods.edit');
Route::put('/goods/{goods_id}', 'GoodsController@update')->name('goods.update');

Route::post('/upload/goods', 'UploadController@goods');

// 某个类目的规格属性
Route::get('/specification', 'SpecificationController@index')->name('specification');

// 商品类目相关的路由
Route::get('/categories', 'CategoryController@index')->name('category.index');
Route::get('/categories/create', 'CategoryController@create')->name('category.create');
Route::post('/categories', 'CategoryController@store')->name('category.store');
Route::get('/categories/{id}/edit', 'CategoryController@edit')->name('category.edit');
Route::put('/categories/{id}', 'CategoryController@update')->name('category.update');

// 订单相关的路由
Route::get('/orders', 'OrderController@index')->name('order.index');
Route::get('/props', 'OrderController@virtual')->name('order.virtual');
Route::get('/delivery', 'OrderController@showDelivery')->name('order.showDelivery');
Route::post('/delivery', 'OrderController@delivery')->name('order.delivery');

// 用户数据分析
Route::get('/player_keep', 'UserStatController@keep')->name('user_stat.keep');
Route::get('/player_numbers', 'UserStatController@number')->name('user_stat.number');