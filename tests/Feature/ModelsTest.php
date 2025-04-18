<?php

use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use function PHPUnit\Framework\assertTrue;

it('model relationship :: product owner should be an user', function () {
    $user = User::factory()->create();
    $product = Product::factory()->recycle($user)->create();

    $owner = $product->owner;

    expect($owner)->toBeInstanceOf(User::class);
});

it('model get mutator :: product title should always be ucfirst', function () {
    $product = Product::factory()->create(['title' => 'titulo']);

    expect($product->title)->toBe('Titulo');
});

it('model set mutator :: product code should be encrypted', function () {
    $product = Product::factory()->create(['code' => 'jeremias']);

    assertTrue(Hash::isHashed($product->code));
});

it('model scopes :: should bring only released products', function () {
    Product::factory(10)->create(['released' => true]);
    Product::factory(5)->create(['released' => false]);

    expect(Product::query()->released()->get())->toHaveCount(10);
});
