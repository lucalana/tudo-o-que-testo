<?php

use App\Models\Product;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/403', function () {
    abort(403);
});

Route::get('/products', function () {
    return view('products', [
        'products' => Product::all(),
    ]);
});
