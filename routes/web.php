<?php

use App\Mail\WelcomeEmail;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

Route::post('/send-email/{user}', function (User $user) {
    Mail::to($user->email)->send(new WelcomeEmail($user));
})->name('send-email');

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

Route::post('/product', function () {
    request()->validate(['title' => ['required', 'max:255']]);

    Product::query()->create(request()->only('title'));

    return response()->json(status: 201);
})->name('product.store');

Route::put('/product/{product}', function (Product $product) {
    $product->update(request()->only('title'));
    return response()->json(status: 200);
})->name('product.update');

Route::delete('/products/{product}/soft', function (Product $product) {
    $product->forceDelete();
    return response()->json(status: 204);
})->name('product.delete');

Route::delete('/products/{product}', function (Product $product) {
    $product->delete();
    return response()->json(status: 204);
})->name('product.soft-delete');
