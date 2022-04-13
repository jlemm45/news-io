<?php

use App\Http\Controllers\FeedController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [FeedController::class, 'index']);

Route::middleware(['auth', 'verified'])->group(function () {
  Route::get('/profile', function () {
    return Inertia::render('Profile');
  })->name('profile');
  Route::get('/saved', [FeedController::class, 'saved'])->name('saved');
  Route::post('/saved/{article}', [FeedController::class, 'saveArticle'])->name(
    'saved.add'
  );
  Route::delete('/saved/{article}', [
    FeedController::class,
    'deleteArticle',
  ])->name('saved.delete');
  Route::post('/feed', [FeedController::class, 'store'])->name('feed.new');
});

require __DIR__ . '/auth.php';
