<?php

use App\Actions\CreateProductAction;
use App\Http\Middleware\JeremiasMiddleware;
use App\Jobs\ImportProducts;
use App\Mail\WelcomeEmail;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

Route::post('/upload-products', function () {
    $file = request()->file('file');
    $openToRead = fopen($file->getRealPath(), 'r');

    while ($data = fgetcsv($openToRead, 1000, ',')) {
        Product::query()->create([
            'title' => $data[0],
            'owner_id' => auth()->id(),
            'code' => 'Code123',
            'released' => true
        ]);
    }

})->name('upload.products');

Route::post('/upload-avatar', function () {
    $file = request()->file('file');
    $file->store('/avatar', 'local');
})->name('upload.avatar');

Route::get('/secure', function () {
})
    ->middleware(JeremiasMiddleware::class)
    ->name('secure.route');

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

    app(CreateProductAction::class)->handle(
        auth()->user(),
        ...request()->only(['title', 'code', 'released'])
    );

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
