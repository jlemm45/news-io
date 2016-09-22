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

//public outside api using api_token auth
Route::group(['prefix' => 'v1', 'middleware' => ['auth:api', 'cors']], function () {
    Route::resource('feed', 'Api\FeedController');
    Route::resource('article', 'Api\ArticleController');
    Route::put('feeds', 'Api\FeedController@updateUserFeeds');
    Route::get('user/status', 'Auth\AuthController@status');
    Route::get('articles', 'Api\ArticleController@index');
});

//public outside api
Route::group(['prefix' => 'v1', 'middleware' => ['cors']], function () {
    Route::post('user/login', 'Auth\AuthController@apiLogin');
    Route::post('user/register', 'Auth\AuthController@register');
});
