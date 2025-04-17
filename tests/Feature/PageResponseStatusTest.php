<?php

use function Pest\Laravel\get;

test('testando código 200', function () {
    get('/')->assertStatus(200);
});

test('testando código 404', function () {
    $response = get('/404');

    $response->assertStatus(404);
});

test('testando código 403', function () {
    $response = get('/403');

    $response->assertStatus(403);
});
