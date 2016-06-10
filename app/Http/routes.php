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

Route::get('/', 'FeedController@welcomeView');
Route::get('/feeds', 'FeedController@feedsView');


Route::group(['prefix' => 'api'], function () {
    Route::resource('feed', 'Api\FeedController');
    //Route::resource('article', 'Api\ArticleController');

    Route::get('articles', 'FeedController@getArticles');
});

