<?php

use App\Models\Product;
use function Pest\Laravel\get;


it('should use the product view', function () {
    $response = get('/products');

    $response->assertViewIs('products');
});

it('should have a variable products', function () {
    $response = get('/products');

    $response->assertViewIs('products')
        ->assertViewHas('products', Product::all());
});
