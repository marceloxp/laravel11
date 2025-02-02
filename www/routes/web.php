<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PageController;
use App\Http\Middleware\Frontend;

Route::middleware([Frontend::class])->group(
    function () {
        Route::get('/', [PageController::class, 'home'])->name('home');
        Route::get('/about', [PageController::class, 'about'])->name('about');
    }
);
