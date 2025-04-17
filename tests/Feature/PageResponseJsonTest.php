<?php

use App\Models\Product;
use function Pest\Laravel\get;

it('it should return the products list', function () {
    $reponse = get('/api/products');

    $reponse->assertOk()->assertExactJson([
        ['title' => 'Produto A'],
        ['title' => 'Produto B'],
    ]);
});

it('should list products in the database', function () {
    //Arrange
    $p1 = Product::factory()->create();
    $p2 = Product::factory()->create();
    //Act
    $response = get('/api/products');
    //Assert
    $response->assertOk()
        ->assertJson([
            ['title' => 'Produto A'],
            ['title' => 'Produto B'],
            ['title' => $p1->title],
            ['title' => $p2->title]
        ]);
});
