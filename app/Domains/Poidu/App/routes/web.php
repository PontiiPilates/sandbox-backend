<?php

use App\Domains\Poidu\App\src\Http\Controllers\AdminController;
use App\Domains\Poidu\App\src\Http\Controllers\FrontendController;
use Illuminate\Support\Facades\Route;

Route::get('/', [FrontendController::class, 'general'])->name('general');
Route::get('/event/{id}', [FrontendController::class, 'event'])->name('event');

Route::get('/hiking', [FrontendController::class, 'hiking'])->name('hiking');
Route::get('/excursions', [FrontendController::class, 'excursions'])->name('excursions');
Route::get('/tours', [FrontendController::class, 'tours'])->name('tours');
Route::get('/mountains', [FrontendController::class, 'mountains'])->name('mountains');
Route::get('/speleo', [FrontendController::class, 'speleo'])->name('speleo');
Route::get('/water', [FrontendController::class, 'water'])->name('water');
Route::get('/tournaments', [FrontendController::class, 'tournaments'])->name('tournaments');
Route::get('/photo', [FrontendController::class, 'photo'])->name('photo');

// todo: с появлением авторизации исправить на административную группу
Route::prefix('shrimp/li/piblz/admin')->name('shrimplipiblz.admin.')->group(function () {
    Route::match(['get', 'post'], '/tg-auth', [AdminController::class, 'tgAuth'])->name('tgAuth');

    Route::get('/published', [AdminController::class, 'published'])->name('published');
    Route::get('/event/{id}/public', [AdminController::class, 'public'])->name('public');
    Route::get('/event/{id}/unpublic', [AdminController::class, 'unPublic'])->name('unPublic');
});
