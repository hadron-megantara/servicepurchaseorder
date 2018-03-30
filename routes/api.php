<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(array('prefix' => 'v1'), function()
{
    Route::post('register', 'Auth\RegisterController@create');
    Route::post('login', 'Auth\LoginController@login');
    Route::post('logout', 'Auth\LoginController@logout');
    Route::post('forgot-password', 'Auth\LoginController@logoutApps');
    Route::post('login-social', 'Auth\LoginController@logoutApps');
    Route::post('refresh-token', 'Auth\LoginController@logoutApps');
    Route::post('resend-activation-mail', 'Auth\LoginController@logoutApps');

  	Route::group(['middleware' => 'apichecktoken'], function () {
  	    // Route::resource('passenger','PassengerController');
  	});

    Route::get('product/list', 'ProductController@getList');
    Route::get('product/detail', 'ProductController@getDetail');
    Route::get('product/detail/photo', 'ProductController@getDetailPhoto');
});
