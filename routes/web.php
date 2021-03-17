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
Route::post('/', 'App\Http\Controllers\WebController@doSave');

Route::get('admin/login', 'App\Http\Controllers\AdminController@login')->name('adminLogin');
Route::post('admin/login', 'App\Http\Controllers\AdminController@doLogin');

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
    Route::post('admin/web/user/update/status', 'App\Http\Controllers\AdminController@webUserUpdateStatus');
    Route::delete('admin/web/user/delete', 'App\Http\Controllers\AdminController@webUserDoDelete');
    
});

