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

    Route::get('customer/list', 'CustomerController@getCustomer');

    Route::get('product/list/event/new', 'ProductController@getListEventNew');
    Route::get('product/list', 'ProductController@getList');
    Route::get('product/detail', 'ProductController@getDetail');
    Route::get('product/detail/photo', 'ProductController@getDetailPhoto');
    Route::get('product/detail/stock', 'ProductController@getDetailStock');
    Route::get('product/detail/list', 'ProductController@getDetailList');
    Route::get('product/detail/photo-by-color', 'ProductController@getPhotoByProducColor');
    Route::post('product/add', 'ProductController@addProduct');
    Route::post('product/image/add', 'ProductController@addImageProduct');

    Route::post('order/add', 'OrderController@create');
    Route::get('order/get', 'OrderController@getOrder');

    Route::get('config/bank-list/get', 'ConfigController@getBankList');
    Route::get('config/bank-account/get', 'ConfigController@getBankAccount');
    Route::post('config/bank-account/add', 'ConfigController@addBankAccount');
    Route::post('config/bank-account/edit', 'ConfigController@editBankAccount');
    Route::post('config/bank-account/delete', 'ConfigController@deleteBankAccount');

    Route::get('config/category/get', 'ConfigController@getCategory');
    Route::post('config/category/add', 'ConfigController@addCategory');
    Route::post('config/category/edit', 'ConfigController@editCategory');
    Route::post('config/category/delete', 'ConfigController@deleteCategory');

    Route::get('config/color/get', 'ConfigController@getColor');
    Route::post('config/color/add', 'ConfigController@addColor');
    Route::post('config/color/edit', 'ConfigController@editColor');
    Route::post('config/color/delete', 'ConfigController@deleteColor');

    Route::get('config/size/get', 'ConfigController@getSize');
    Route::get('config/gender/get', 'ConfigController@getGender');
    Route::get('config/discount-type/get', 'ConfigController@getDiscount');
    Route::get('config/province/get', 'ConfigController@getProvince');
    Route::get('config/city/get', 'ConfigController@getCity');
    Route::get('config/district/get', 'ConfigController@getDistrict');
});
