<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PageController;
use App\Http\Middleware\Frontend;
use App\Http\Middleware\AutoAssetsMiddleware;

Route::middleware([Frontend::class, AutoAssetsMiddleware::class])->group(
    function () {
        Route::get('/', [PageController::class, 'home'])->name('home');
        Route::get('/about', [PageController::class, 'about'])->name('about');
    }
);
