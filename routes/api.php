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
    // Page endpoints
    Route::post('add_link', 'PageController@add_link');
    Route::post('remove_link', 'PageController@remove_link');
    Route::put('edit_description', 'PageController@edit_description');
    Route::put('edit_link/{id}', 'PageController@edit_link');
    Route::put('edit_page/{id}', 'PageController@edit_page');
    Route::put('edit_theme', 'PageController@edit_theme');
    Route::post('add_avatar', 'PageController@avatar');
    Route::post('get_stat', 'PageController@retrieve_stat');

    // Tarifs endpoints
    Route::post('update_tarif', 'TarifsController@updateTarif');
    Route::post('change_to_basic', 'TarifsController@changeToBasic');

    Route::post('success', 'TarifsController@success');
    Route::get('fail', 'TarifsController@fail');

});
Route::post('pay', 'TarifsController@pay');
Route::get('get_links/{instagram}', 'PageController@get_links');
Route::get('get_account_types', 'TarifsController@getAccountTypes');
Route::post('add_stat', 'PageController@add_stat');

Route::get('/homepage', function() {

});
