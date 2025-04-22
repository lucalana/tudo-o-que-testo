<?php

use App\Actions\CreateProductAction;
use App\Models\User;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\mock;
use function Pest\Laravel\post;


it('should call the action to create a product', function () {
    Notification::fake();
    //Assert
    mock(CreateProductAction::class)->shouldReceive('handle')->atLeast()->once();
    //Arrange
    $user = User::factory()->create();
    actingAs($user);
    $data = [
        'title' => 'Produto legal!',
        'code' => 'code1234',
        'released' => true,
    ];
    //Act
    post(route('product.store'), $data);
});

it('should create a product', function () {
    //Assert
    //Arrange
    $user = User::factory()->create();
    $data = [
        'title' => 'Produto legal!',
        'code' => 'code1234',
        'released' => true,
    ];
    (new CreateProductAction())->handle($user, ...$data);
    //Act
    assertDatabaseCount('products', 1);
    assertDatabaseHas('products', ['title' => 'Produto legal!']);
});
