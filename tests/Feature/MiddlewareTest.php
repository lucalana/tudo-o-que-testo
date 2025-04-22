<?php

use App\Http\Middleware\JeremiasMiddleware;
use App\Models\User;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Laravel\mock;

it('/should block an request if the user does not have the following email: jeremias@gmail.com/', function () {
    $user = User::factory()->create(['email' => 'email@qualquer.com']);
    $jeremias = User::factory()->create(['email' => 'jeremias@gmail.com']);

    actingAs($user);
    get(route('secure.route'))->assertForbidden();

    actingAs($jeremias);
    get(route('secure.route'))->assertOk();
});

it('checks if is being called', function () {
    Mock(JeremiasMiddleware::class)->shouldReceive('handle')->atLeast()->once();

    get(route('secure.route'));
});

