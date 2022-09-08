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

Route::post('login','Auth\AuthController@login');
Route::post('register',"Auth\AuthController@register");
Route::get('logout','Auth\AuthController@logout');

Route::get('/', function () {
    return response()->json('welcome', 200);
});