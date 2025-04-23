<?php

use App\Actions\CreateProductAction;
use App\Actions\ExportProductToAmazon;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;

it('should fake an api request', function () {
    Http::fake([
        'https:://api.amazon.com/products' => Http::response([
            ['title' => 'Product 1'],
            ['title' => 'Product 2'],
        ])
    ]);
    $user = User::factory()->create();
    $response = Http::get('https:://api.amazon.com/products');
    foreach ($response->json() as $product) {
        app(CreateProductAction::class)->handle($user, $product['title'], 'Code123', true);
    }
    assertDatabaseCount('products', 2);
    assertDatabaseHas('products', ['title' => 'Product 1', 'owner_id' => $user->id]);
    assertDatabaseHas('products', ['title' => 'Product 2', 'owner_id' => $user->id]);
});

it('test the data we sent to amazon', function () {
    Http::fake();

    $produtos = Product::factory(2)->create();

    (new ExportProductToAmazon)->handle($produtos);

    Http::assertSent(function (Request $request) use ($produtos) {
        return $request->url() === 'https://api.amazon.com/products'
            && $request->data() === array_map(fn($p) => ['title' => $p['title']], $produtos->toArray());
    });
});

it('should have the key', function () {
    expect(config('services'))
        ->toHaveKey('amazon')
        ->and(config('services.amazon'))
        ->toHaveKey('api_key');
});
