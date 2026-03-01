<?php

use App\Domains\Parsing\Http\Controllers\ParserController;
use Illuminate\Support\Facades\Route;

Route::prefix('api/v1/parsing')->group(function () {
    Route::get('/test', [ParserController::class, 'test'])->name('test');
});
