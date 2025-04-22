<?php

use App\Models\User;
use App\Notifications\NewProductNotification;
use Illuminate\Support\Facades\Notification;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\post;

it('should send a notification about a new product', function () {
    //Arrange
    Notification::fake();
    $user = User::factory()->create();
    //Act
    actingAs($user);
    $request = post(route('product.store'), [
        'title' => 'Produto legal!',
        'code' => 'code1234',
        'released' => true,
    ]);
    //Assert
    $request->assertCreated();

    Notification::assertCount(1);
    Notification::assertSentTo([$user], NewProductNotification::class);
});
