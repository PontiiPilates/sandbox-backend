<?php

use App\Domains\Poidu\Http\Controllers\CategoryController;
use App\Domains\Poidu\Http\Controllers\EventController;
use App\Domains\Poidu\Http\Controllers\PoiduController;
use Illuminate\Support\Facades\Route;

Route::prefix('api/v1/poidu')->group(function () {
    Route::get('/ping', [PoiduController::class, 'ping'])->name('ping');

    Route::get('/search', [PoiduController::class, 'search'])->name('search');
    Route::get('/events', [EventController::class, 'index'])->name('events');
    Route::get('/event/{id}', [EventController::class, 'show'])->name('event');

    Route::get('/categories', [CategoryController::class, 'index'])->name('categories');

    Route::get('/admin/01KK6RJMFJTMMNBGA0N5KAN1Y2', [CategoryController::class, 'admin'])->name('admin');
    Route::get('/admin/01KK6RJMFJTMMNBGA0N5KAN1Y2/events', [CategoryController::class, 'admin'])->name('admin');
    Route::get('/admin/01KK6RJMFJTMMNBGA0N5KAN1Y2/events/public', [CategoryController::class, 'admin'])->name('admin');
    Route::get('/admin/01KK6RJMFJTMMNBGA0N5KAN1Y2/events/unpublic', [CategoryController::class, 'admin'])->name('admin');
});