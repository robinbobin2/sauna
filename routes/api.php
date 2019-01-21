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
    Route::post('login', 'AuthController@login');
    Route::post('signup', 'AuthController@signup');

    Route::group([
      'middleware' => 'auth:api'
  ], function() {
    Route::get('logout', 'AuthController@logout');
    Route::get('user', 'AuthController@user');

});
});

Route::group([
  'middleware' => 'auth:api'
], function() {
    Route::post('add_link', 'PageController@add_link');
    Route::post('remove_link', 'PageController@remove_link');
    Route::put('edit_link', 'PageController@edit_link');
    Route::put('edit_description', 'PageController@edit_description');
    Route::get('get_links', 'PageController@get_links');
});

Route::get('/homepage', function() {

});