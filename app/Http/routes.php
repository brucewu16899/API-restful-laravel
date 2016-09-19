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

$api = app('Dingo\Api\Routing\Router');

Route::get('/', function () {
    return view('welcome');
});


$api->version('v1',function($api){

	//http://localhost:8000/api/users
	$api->get('users','App\Http\Controllers\HomeController@index');
	$api->get('users/{user_id}/roles/{role_name}','App\Http\Controllers\HomeController@attachUserRole');
	$api->get('users/{user_id}/roles','App\Http\Controllers\HomeController@getUserRole');

	$api->post('role/permission/add', 'App\Http\Controllers\HomeController@attachPermission');
	$api->get('role/{role_name}/permissions', 'App\Http\Controllers\HomeController@getPermissions');

	$api->post('authenticate', 'App\Http\Controllers\Auth\AuthController@authenticate');

});


$api->version('v1', ['middleware' => 'jwt.auth'], function($api){

	$api->get('users', 'App\Http\Controllers\Auth\AuthController@index');
	$api->get('users/{user_id}', 'App\Http\Controllers\Auth\AuthController@show');
	$api->get('token', 'App\Http\Controllers\Auth\AuthController@getToken');

});

