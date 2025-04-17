<?php

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/products', function () {
    $products = Product::all()
        ->map(fn($p) => ['title' => $p->title])
        ->toArray();
    return [
        ['title' => 'Produto A'],
        ['title' => 'Produto B'],
        ...$products
    ];
});
