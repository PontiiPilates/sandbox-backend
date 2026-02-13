<?php

use App\Domains\Poidu\Http\Controllers\PoiduController;
use Illuminate\Support\Facades\Route;

Route::prefix('api/v1/poidu')->group(function () {
    Route::get('/ping', [PoiduController::class, 'ping'])->name('ping');

    Route::get('/search', [PoiduController::class, 'search'])->name('search');
    Route::get('/events', [PoiduController::class, 'events'])->name('events');
    Route::get('/event/{id}', [PoiduController::class, 'event'])->name('event');
});
