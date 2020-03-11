<?php

use Illuminate\Http\Request;
use InstagramAPI\Instagram;

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

Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', 'UserController@login');
    Route::post('signup', 'UserController@signup');
    Route::post('verify', 'UserController@verify');

    Route::group([
        'middleware' => 'auth:api'
    ], function () {
        Route::get('logout', 'UserController@logout');
        Route::get('user', 'UserController@user');
    });
});

Route::group([
    'middleware' => 'auth:api'
], function () {
    // Page endpoints
    Route::post('bookings', 'UserController@userBookings');
    Route::post('book', 'UserController@book');
    Route::post('unbook', 'UserController@unbook');
});
