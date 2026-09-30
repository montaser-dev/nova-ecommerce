<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Home');
});

Route::get('/home', function () {
    return Inertia::render('Home');
})->middleware('auth');

Route::get('/account', function () {
    return Inertia::render('Account/Profile');
})->middleware('auth');

Route::get('/products', [ProductController::class, 'index'])->name('products.index');

Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');
