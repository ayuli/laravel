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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

//微信
Route::get('/weixin/valid1', 'weixin\weixinContorller@wxEven');


Route::get('/weixin/user/list', 'weixin\weixinContorller@userList');// 用户列表

//Route::get('/weixin/token', 'weixin\weixinContorller@wxAccessToken');
//Route::get('/weixin', 'weixin\weixinContorller@getUserInfo');
//Route::get('/weixin/user', 'weixin\weixinContorller@wxUserInfo');


