<?php

use App\Domains\Poidu\Http\Controllers\PoiduController;
use Illuminate\Support\Facades\Route;

Route::prefix('api/v1/poidu')->group(function () {
    Route::get('/test', [PoiduController::class, 'test'])->name('test');
});
