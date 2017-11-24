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

// Route::get('/', function () {
//     return view('welcome');
// });



Route::get('/', 'HomeController@index')->name('home');
Route::get('home', 'HomeController@index');
Route::post('home/index', 'HomeController@index');
// Auth::routes();

/**
 * 登陆，注册，退出，密码找回
 */
Route::get('admin/login','AdminController@login');
Route::post('admin/signin','AdminController@signin');

Route::get('admin/register','AdminController@register');
Route::post('admin/signup','AdminController@signup');
Route::get('admin/confirm/{id}','AdminController@confirm');

Route::post('admin/logout','AdminController@logout');

Route::get('admin/password','AdminController@password');
Route::post('admin/password/email','AdminController@findPassword');
Route::get('admin/password/reset/{id}','AdminController@reset');
Route::post('admin/password/request','AdminController@resetPassword');
/**
 * 主页显示
 */
Route::get('article/show/{id}', 'ArticleController@show');
Route::get('article/index', 'ArticleController@indexBysearch');
Route::get('article/index/tag/{id}', 'ArticleController@indexByTag');

/**
 * 登陆后操作
 */
Route::group(['prefix'=>'admin','namespace'=>'Admin','middleware'=>'admingroup'],function(){
    Route::resource('article','ArticleController');
    Route::post('upload/picture', 'UploadController@picture');
    
});

/**
 * 微信接口
 */
Route::group(['namespace'=>'Wechat'],function(){
    Route::any('wechat', 'WeChatController@serve');
    Route::get('wechat/handle', 'WeChatController@handle');
});

/**
 * api接口
 */
Route::get('apitest/index', 'ApiTestController@index');
Route::get('apitest/send', 'ApiTestController@send');