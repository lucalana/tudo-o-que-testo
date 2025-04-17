<?php

use App\Models\Product;
use function Pest\Laravel\get;

it('should list products', function () {
    //Arrange
    //Act
    $response = get('/products');
    //Assert
    $response->assertOk()->assertSeeTextInOrder([
        'Produto A',
        'Produto B'
    ]);
});

it('should list products in the database', function () {
    //Arrange
    $p1 = Product::factory()->create();
    $p2 = Product::factory()->create();
    //Act
    $response = get('/products');
    //Assert
    $response->assertOk()->assertSeeTextInOrder([
        'Produto A',
        'Produto B',
        $p1->title,
        $p2->title,
    ]);
});
