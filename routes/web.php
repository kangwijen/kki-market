<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Request;

Route::get('/', function () {
    return view('index');
})->name('index');

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::get('/search', function () {
    $products = [
        [
            'name' => 'Product 1',
            'price' => 100,
            'image' => 'https://picsum.photos/200',
            'id' => 1,
            'stock' => 10,
            'sold' => 5,
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam nec purus nec nunc ultricies ultricies. Nullam nec purus nec nunc ultricies ultricies. Nullam nec purus nec nunc ultricies ultricies. Nullam nec purus nec nunc ultricies ultricies.',
        ],
        [
            'name' => 'Product 2',
            'price' => 200,
            'image' => 'https://picsum.photos/200',
            'id' => 2,
            'stock' => 10,
            'sold' => 5,
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam nec purus nec nunc ultricies ultricies. Nullam nec purus nec nunc ultricies ultricies. Nullam nec purus nec nunc ultricies ultricies. Nullam nec purus nec nunc ultricies ultricies.',
        ],
        [
            'name' => 'Product 3',
            'price' => 300,
            'image' => 'https://picsum.photos/200',
            'id' => 3,
            'stock' => 10,
            'sold' => 5,
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam nec purus nec nunc ultricies ultricies. Nullam nec purus nec nunc ultricies ultricies. Nullam nec purus nec nunc ultricies ultricies. Nullam nec purus nec nunc ultricies ultricies.',
        ],
    ];
    
    return view('marketplace.search', compact('products'));
})->name('search');

Route::get('/products/{product}', function ($product) {
    $products = [
        [
            'name' => 'Product 1',
            'price' => 100,
            'image' => 'https://picsum.photos/200',
            'id' => 1,
            'stock' => 10,
            'sold' => 5,
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam nec purus nec nunc ultricies ultricies. Nullam nec purus nec nunc ultricies ultricies. Nullam nec purus nec nunc ultricies ultricies. Nullam nec purus nec nunc ultricies ultricies.',
        ],
        [
            'name' => 'Product 2',
            'price' => 200,
            'image' => 'https://picsum.photos/200',
            'id' => 2,
            'stock' => 10,
            'sold' => 5,
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam nec purus nec nunc ultricies ultricies. Nullam nec purus nec nunc ultricies ultricies. Nullam nec purus nec nunc ultricies ultricies. Nullam nec purus nec nunc ultricies ultricies.',
        ],
        [
            'name' => 'Product 3',
            'price' => 300,
            'image' => 'https://picsum.photos/200',
            'id' => 3,
            'stock' => 10,
            'sold' => 5,
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam nec purus nec nunc ultricies ultricies. Nullam nec purus nec nunc ultricies ultricies. Nullam nec purus nec nunc ultricies ultricies. Nullam nec purus nec nunc ultricies ultricies.',
        ],
    ];

    foreach ($products as $p) {
        if ($p['id'] == $product) {
            return view('marketplace.product', compact('p'));
        }
    }
    
    return view('marketplace.product');
})->name('products');

Route::fallback(function () {
    return view('errors.404');
});