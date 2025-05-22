<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Home');
})->name('home');

Route::get('gradicos', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('graficos');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
