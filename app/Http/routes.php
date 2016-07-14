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

Route::get('/', 'FeedController@welcomeView')->middleware('guest');
Route::get('/feeds', 'FeedController@feedsView');
Route::get('/login', function(){
    return view('pages.login');
});
Route::get('/me', function(){
    return view('pages.me');
})->middleware('auth');

Route::get('/register', function(){
    return view('pages.register');
});

// Authentication routes...
//Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@login');
Route::get('auth/logout', 'Auth\AuthController@getLogout');

// Registration routes...
//Route::get('auth/register', 'Auth\AuthController@getRegister');
Route::get('auth/status', 'Auth\AuthController@status');
Route::post('auth/register', 'Auth\AuthController@register');

//internal api
Route::group(['prefix' => 'api'], function () {
    Route::resource('feed', 'Api\FeedController');
    Route::resource('article', 'Api\ArticleController');
    Route::put('user', 'UserController@updatePassword');
    Route::put('feeds', 'Api\FeedController@updateUserFeeds');

    Route::get('articles', 'FeedController@getArticles');

    Route::get('socket', function() {
        return env('SOCKET_URL');
    });

    Route::group(['middleware' => ['admin'], 'prefix' => 'data'], function () {
        Route::get('articles', 'Api\DataController@getArticlesAddedData');
        Route::get('users', 'Api\DataController@getUserCount');
    });
});

//public outside api using api_token auth
Route::group(['prefix' => 'api/v1', 'middleware' => ['auth:api', 'cors']], function () {
    Route::resource('feed', 'Api\FeedController');
    Route::resource('article', 'Api\ArticleController');
    Route::put('feeds', 'Api\FeedController@updateUserFeeds');
    Route::get('user/status', 'Auth\AuthController@status');
    Route::get('articles', 'FeedController@getArticles');
});

//public outside api
Route::group(['prefix' => 'api/v1', 'middleware' => ['cors']], function () {
    Route::post('user/login', 'Auth\AuthController@apiLogin');
});

Route::group(['middleware' => ['admin'], 'prefix' => 'admin'], function () {
    Route::get('/', function() {
        return view('admin.dashboard');
    });
});
