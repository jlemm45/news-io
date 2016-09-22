<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
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
Route::get('auth/logout', 'Auth\AuthController@logout');

// Registration routes...
//Route::get('auth/register', 'Auth\AuthController@getRegister');
Route::get('auth/status', 'Auth\AuthController@status');
Route::post('auth/register', 'Auth\AuthController@register');

Route::group(['middleware' => ['admin'], 'prefix' => 'admin'], function () {
    Route::get('/', function() {
        return view('admin.dashboard');
    });
});

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
