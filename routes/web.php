<?php

use Illuminate\Support\Facades\Route;

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
Route::get('/', 'App\Http\Controllers\WebController@index');
Route::get('/downloadUrl', 'App\Http\Controllers\WebController@downloadUrl');
Route::get('/logout', 'App\Http\Controllers\WebController@logout');
Route::post('api/web/user/doLogin', 'App\Http\Controllers\WebController@doLogin');
Route::post('api/web/user/reg', 'App\Http\Controllers\WebController@reg');

Route::get('admin/login', 'App\Http\Controllers\AdminController@login')->name('adminLogin');
Route::post('admin/login', 'App\Http\Controllers\AdminController@doLogin');

//for bot
Route::any('bot/reply', 'App\Http\Controllers\BotController@reply');

//login
Route::group(['middleware' => ['auth']], function () {
    Route::get('admin', 'App\Http\Controllers\AdminController@index');
    Route::get('admin/logout', 'App\Http\Controllers\AdminController@logout');
    Route::get('admin/user/edit', 'App\Http\Controllers\AdminController@adminUserEdit')->name('adminUserEdit');
    Route::get('admin/user/create', 'App\Http\Controllers\AdminController@adminUserEdit')->name('adminUserCreate');
    
    Route::post('admin/user/doEdit', 'App\Http\Controllers\AdminController@adminUserDoEdit');
    Route::delete('admin/user/delete', 'App\Http\Controllers\AdminController@adminUserDoDelete');

    //web user
    Route::get('admin/web/user/list', 'App\Http\Controllers\AdminController@webUserList');
    Route::get('admin/web/user/edit', 'App\Http\Controllers\AdminController@webUserEdit')->name('webUserEdit');
    Route::get('admin/web/user/create', 'App\Http\Controllers\AdminController@webUserEdit')->name('webUserCreate');
    Route::post('admin/web/user/doEdit', 'App\Http\Controllers\AdminController@webUserDoEdit');

    //setting
    Route::get('admin/settings', 'App\Http\Controllers\AdminController@settings');
    Route::post('admin/settings', 'App\Http\Controllers\AdminController@saveSettings');
    
});
