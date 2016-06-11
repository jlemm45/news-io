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

// Authentication routes...
//Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@login');
Route::get('auth/logout', 'Auth\AuthController@getLogout');

// Registration routes...
//Route::get('auth/register', 'Auth\AuthController@getRegister');
Route::get('auth/status', 'Auth\AuthController@status');
Route::post('auth/register', 'Auth\AuthController@register');


Route::group(['prefix' => 'api'], function () {
    Route::resource('feed', 'Api\FeedController');
    Route::resource('article', 'Api\ArticleController');
    Route::put('feeds', 'Api\FeedController@updateUserFeeds');

    Route::get('articles', 'FeedController@getArticles');
});

