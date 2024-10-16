<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductTypeController;
use App\Http\Controllers\RegisterController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [RegisterController::class, 'register']);

Route::prefix('/api')->middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/product/{id}', [ProductController::class, 'show']);

    Route::middleware('admin')->group(function () {
        Route::post('/product', [ProductController::class, 'store']);
        Route::put('/product/{product}', [ProductController::class, 'update']);
        Route::delete('/product/{product}', [ProductController::class, 'destroy']);
    });

    Route::get('/product-types', [ProductTypeController::class, 'index']);

    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart', [CartController::class, 'store']);
    Route::put('/cart/{product_id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{id}', [CartController::class, 'destroy']);
});

Route::get('/', function () {
    return view('layouts.app');
})->name('index');

Route::get('/login', function () {
    return Auth::check() ? redirect('/search') : view('layouts.app');
})->name('login');

Route::get('/register', function () {
    return Auth::check() ? redirect('/search') : view('layouts.app');
})->name('register');

Route::middleware('auth')->group(function () {
    Route::get('/search', function () {
        return view('layouts.app');
    })->name('search');
    Route::get('/product/{id}', function () {
        return view('layouts.app');
    })->name('product.details');
    Route::get('/cart', function () {
        return view('layouts.app');
    })->name('cart');
    Route::middleware('admin')->group(function () {
        Route::get('/admin', function () {
            return view('layouts.app');
        })->name('admin');
    });
});

Route::fallback(function () {
    return view('layouts.app');
});
