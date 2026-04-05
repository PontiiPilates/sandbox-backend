<?php

use App\Domains\Poidu\App\src\Http\Controllers\AdminController;
use App\Domains\Poidu\App\src\Http\Controllers\CategoryController;
use App\Domains\Poidu\App\src\Http\Controllers\EventController;
use App\Domains\Poidu\App\src\Http\Controllers\PoiduController;
use Illuminate\Support\Facades\Route;

Route::prefix('api/v1/poidu')->group(function () {
    Route::get('/ping', [PoiduController::class, 'ping'])->name('ping');

    Route::get('/events', [EventController::class, 'index'])->name('events');
    Route::get('/event/{id}', [EventController::class, 'show'])->name('event');

    Route::get('/categories', [CategoryController::class, 'index'])->name('categories');

    /**
     * Административная часть
     */
    Route::get('/admin/01KK6RJMFJTMMNBGA0N5KAN1Y2/events', [AdminController::class, 'events'])->name('admin.events');
    Route::patch('/admin/01KK6RJMFJTMMNBGA0N5KAN1Y2/event/{id}/public', [AdminController::class, 'public'])->name('admin.public');
    Route::patch('/admin/01KK6RJMFJTMMNBGA0N5KAN1Y2/event/{id}/unpublic', [AdminController::class, 'unPublic'])->name('admin.unPublic');
});
