<?php

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
