<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Home');
})->name('home');

Route::get('/graficos', [DashboardController::class, 'index'])->name('graficos');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
