<?php

use App\Models\Product;
use App\Models\User;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseEmpty;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\assertSoftDeleted;
use function Pest\Laravel\delete;
use function Pest\Laravel\post;
use function Pest\Laravel\put;
use function PHPUnit\Framework\assertSame;

it('should be able to create a product', function () {
    //Arrange
    $user = User::factory()->create();
    actingAs($user);
    //Act
    post(route('product.store'), [
        'title' => 'Produto legal!',
        'code' => 'code1234',
        'released' => true,
    ])->assertCreated();
    //Assert
    assertDatabaseHas('products', ['title' => 'Produto legal!']);
    assertDatabaseCount('products', 1);
});

it('should be able to update a product', function () {
    //Arrange
    $product = Product::factory()->create();
    //Act
    put(route('product.update', $product->id), [
        'title' => 'Troca essa desgrama'
    ])
        ->assertOk();
    //Assert
    assertDatabaseHas('products', [
        'id' => $product->id,
        'title' => 'Troca essa desgrama'
    ]);
    expect($product)->refresh()->title->toBe('Troca essa desgrama');
    assertSame('Troca essa desgrama', $product->title);
    assertDatabaseCount('products', 1);
});

it('should be able to delete a product', function () {
    //Arrange
    $product = Product::factory()->create();
    //Act
    delete(route('product.delete', $product->id))->assertNoContent();
    //Arrange
    assertDatabaseEmpty('products');
    assertDatabaseMissing('products', ['id' => $product->id]);
});

it('should be able to softdelete a product', function () {
    //Arrange
    $product = Product::factory()->create();
    //Act
    delete(route('product.soft-delete', $product->id))->assertNoContent();
    //Arrange
    assertSoftDeleted('products', ['id' => $product->id]);
    assertDatabaseCount('products', 1);
});
