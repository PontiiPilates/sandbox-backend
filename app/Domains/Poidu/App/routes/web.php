<?php

use App\Domains\Poidu\App\src\Http\Controllers\FrontendController;
use Illuminate\Support\Facades\Route;

// Route::prefix('p')->group(function () {

Route::get('/ping', [FrontendController::class, 'ping'])->name('ping');
Route::get('/', [FrontendController::class, 'general'])->name('general');
Route::get('/event/{id}', [FrontendController::class, 'event'])->name('event');
// });
