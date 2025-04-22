<?php

use App\Console\Commands\CreateProductCommand;
use App\Models\User;
use function Pest\Laravel\artisan;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;

it('should be able to create a product via command', function () {
    $user = User::factory()->create();

    artisan(CreateProductCommand::class, [
        'title' => 'Produto legal!',
        'code' => 'code1234',
        'user' => $user->id
    ]);

    assertDatabaseCount('products', 1);
    assertDatabaseHas('products', [
        'title' => 'Produto legal!',
        'owner_id' => $user->id,
    ]);
});

it('it should ask for user, title and code', function () {
    $user = User::factory()->create();

    artisan(CreateProductCommand::class, [
        'code' => 'code1234',
    ])->expectsQuestion('Please provide a valid user id', $user->id)
    ->expectsQuestion('Please provide a product title', 'Produto legal!')
    ->expectsOutputToContain('Product created.')
    ->assertSuccessful();
});
