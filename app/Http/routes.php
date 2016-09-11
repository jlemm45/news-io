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

Route::get('/article/{id}', 'ArticleController@showArticle');

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

    Route::get('articles', 'Api\ArticleController@index');

    //admin data analytics api
    Route::group(['middleware' => ['admin'], 'prefix' => 'data'], function () {
        Route::get('articles', 'Api\DataController@getArticlesAddedData');
        Route::get('users-count', 'Api\DataController@getUserCount');
        Route::get('articles', 'Api\DataController@getArticlesAddedData');
        Route::resource('users', 'Api\UserController');
    });
});

//public outside api using api_token auth
Route::group(['prefix' => 'api/v1', 'middleware' => ['auth:api', 'cors']], function () {
    Route::resource('feed', 'Api\FeedController');
    Route::resource('article', 'Api\ArticleController');
    Route::put('feeds', 'Api\FeedController@updateUserFeeds');
    Route::get('user/status', 'Auth\AuthController@status');
    Route::get('articles', 'Api\ArticleController@index');
});

//public outside api
Route::group(['prefix' => 'api/v1', 'middleware' => ['cors']], function () {
    Route::post('user/login', 'Auth\AuthController@apiLogin');
    Route::post('user/register', 'Auth\AuthController@register');
});

Route::group(['middleware' => ['admin'], 'prefix' => 'admin'], function () {
    Route::get('/', function() {
        return view('admin.dashboard');
    });
});
