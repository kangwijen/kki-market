<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RegisterController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [RegisterController::class, 'register']);

Route::prefix('/api')->middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/product/{id}', [ProductController::class, 'show']);
    
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart', [CartController::class, 'store']);
    Route::put('/cart/{id}', [CartController::class, 'update']);
    Route::delete('/cart/{id}', [CartController::class, 'destroy']);
});

Route::get('/', function () {
    return view('layouts.app');
})->name('index');
Route::get('/login', function () {
    return view('layouts.app');
})->name('login');
Route::get('/register', function () {
    return view('layouts.app');
})->name('register');
Route::get('/search', function () {
    return view('layouts.app');
})->name('search')->middleware('auth');
Route::get('/product/{id}', function () {
    return view('layouts.app');
})->name('product.details')->middleware('auth');
Route::get('/cart', function () {
    return view('layouts.app');
})->name('cart')->middleware('auth');

Route::fallback(function () {
    return view('layouts.app');
});