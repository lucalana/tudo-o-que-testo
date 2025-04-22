<?php

use App\Actions\CreateProductAction;
use App\Models\User;
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
