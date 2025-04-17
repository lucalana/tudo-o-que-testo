<?php

use function Pest\Laravel\get;

test('testando código 200', function () {
    //Arrange
    //Act Assert
    get('/')->assertStatus(200);
});

test('testando código 404', function () {
    //Arrange
    //Act
    $response = get('/404');
    //Assert
    $response->assertStatus(404);
});

test('testando código 403', function () {
    //Arrange
    //Act
    $response = get('/403');
    //Assert
    $response->assertStatus(403);
});
