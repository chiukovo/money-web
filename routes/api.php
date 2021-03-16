<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('checkAccount', 'App\Http\Controllers\WebController@checkAccount');
Route::post('sendPhoneCode', 'App\Http\Controllers\WebController@sendPhoneCode');
