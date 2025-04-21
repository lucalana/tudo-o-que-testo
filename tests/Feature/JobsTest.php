<?php

use App\Jobs\ImportProducts;
use App\Models\User;
use Illuminate\Support\Facades\Queue;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\post;

it('should dispatch a job to the queue', function () {
    //Arrange
    Queue::fake();
    $user = User::factory()->create();
    //Act
    actingAs($user);
    post(route('product.import'), [
        'produtos' => [
            ['title' => 'Product 1'],
            ['title' => 'Product 2'],
            ['title' => 'Product 3'],
        ]
    ]);
    //Assert
    Queue::assertPushed(ImportProducts::class);
});

it('should import products', function () {
    //Arrange
    $user = User::factory()->create();
    $data = [
        ['title' => 'Product 1'],
        ['title' => 'Product 2'],
        ['title' => 'Product 3'],
    ];
    //Act
    (new ImportProducts($data, $user->id))->handle();
    //Assert
    assertDatabaseCount('products', 3);
    assertDatabaseHas('products', ['title' => 'Product 1']);
    assertDatabaseHas('products', ['title' => 'Product 2']);
    assertDatabaseHas('products', ['title' => 'Product 3']);
});
