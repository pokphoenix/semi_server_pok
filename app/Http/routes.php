<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

header('Access-Control-Allow-Origin:  *');
header('Access-Control-Allow-Methods:  POST, GET, OPTIONS, PUT, DELETE');
header('Access-Control-Allow-Headers:  Content-Type, X-Auth-Token, Origin, Authorization');

Route::get('/', function () {
    return view('welcome');
});

//--- Route ที่ไม่มีการเช็ค auth user
Route::group(['prefix' => 'api', 'middleware' => []], function () {
    Route::post('auth', 'AuthController@Login');
});

//--- Route ที่มีการเช็ค auth user
Route::group(['prefix' => 'api', 'middleware' => ['jwt.auth','permission']], function () {
    Route::controller('auth', 'AuthController');
    Route::resource('users', 'UserController');
    Route::resource('branch', 'BranchController');
    Route::resource('permission', 'PermissionController');
    Route::resource('role', 'RoleController');
    Route::resource('usertype', 'UserTypeController');
});

