<?php

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\post;

it('should upload an image', function () {
    Storage::fake();
    $user = User::factory()->create();
    actingAs($user);

    $file = UploadedFile::fake()->image('image.jpg');

    post(route('upload.avatar'), [
        'file' => $file
    ])->assertOk();

    Storage::disk('local')->assertExists('/avatar/' . $file->hashName());
});

it('should upload an csv', function () {
    Storage::fake();
    $user = User::factory()->create();
    actingAs($user);
    $data = <<<txt
    Product 1
    Product 2
    txt;
    //Act
    $file = UploadedFile::fake()->createWithContent('products.csv', $data);
    post(route('upload.products'), [
        'file' => $file
    ])->assertOk();
    //Assert
    assertDatabaseCount('products', 2);
    assertDatabaseHas('products', ['title' => 'Product 1', 'owner_id' => $user->id]);
    assertDatabaseHas('products', ['title' => 'Product 2', 'owner_id' => $user->id]);
});

