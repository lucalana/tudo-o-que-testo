<?php

use App\Jobs\ImportProducts;
use App\Mail\WelcomeEmail;
use App\Models\Product;
use App\Models\User;
use App\Notifications\NewProductNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

Route::post('/import-product', function () {
    $produtos = request()->only('produtos');
    ImportProducts::dispatch($produtos, auth()->id());
})->name('product.import');

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
    request()->validate([
        'title' => ['required', 'max:255'],
        'code' => ['required'],
        'released' => ['required'],
    ]);
    Product::query()->create([
        'owner_id' => auth()->id(),
        ...request()->only(['title', 'code', 'released'])
    ]);
    auth()->user()->notify(new NewProductNotification());
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
