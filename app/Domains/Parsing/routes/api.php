<?php

use App\Domains\Parsing\Http\Controllers\ParsingController;
use Illuminate\Support\Facades\Route;

Route::prefix('api/v1/parsing')->group(function () {
    Route::get('/tg-auth', [ParsingController::class, 'tgAuth'])->name('tg-auth');
    Route::get('/show-extract', [ParsingController::class, 'showExtract'])->name('show-extract');
});
