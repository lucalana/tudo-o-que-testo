<?php

use App\Mail\WelcomeEmail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use function Pest\Laravel\post;

it('It should sent an email', function () {
    //Arrange
    Mail::fake();
    $user = User::factory()->create();
    //Act
    $request = post(route('send-email', $user->id));
    //Assert
    $request->assertOk();
    Mail::assertSent(WelcomeEmail::class);
});

it('It should sent an email to a user x', function () {
    //Arrange
    Mail::fake();
    $user = User::factory()->create();
    //Act
    $request = post(route('send-email', $user->id));
    //Assert
    $request->assertOk();
    Mail::assertSent(WelcomeEmail::class, fn(WelcomeEmail $email) => $email->hasTo($user->email));
});

it('It should contain the user name in the subject', function () {
    //Arrange
    $user = User::factory()->create();
    $email = new WelcomeEmail($user);
    //Act
    //Assert
    expect($email)->assertHasSubject('Thank you ' . $user->name);
});

it('It should contain the user email with a text', function () {
    //Arrange
    $user = User::factory()->create();
    $email = new WelcomeEmail($user);
    //Act
    //Assert
    expect($email)->assertSeeInHtml('Confirmando que seu email é ' . $user->email);
});
