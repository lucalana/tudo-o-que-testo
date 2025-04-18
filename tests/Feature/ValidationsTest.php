<?php

use function Pest\Laravel\post;

it('product :: title should be required', function () {
    //Arrange
    //Act
    $request = post(route('product.store'), ['title' => '']);
    //Assert
    $request->assertInvalid(['title' => 'required']);
});

it('product :: title should be max 255 character', function () {
    //Arrange
    //Act
    $request = post(route('product.store'), ['title' => str_repeat('*', 300)]);
    //Assert
    $request->assertInvalid([
        'title' => trans('validation.max.string', ['attribute' => 'title', 'max' => 255])
    ]);
});

// OUTRA FORMA DE FAZER ESSAS MESMAS VALIDAÇÕES

it('product validations', function ($data, $error) {
    //Arrange
    //Act
    $request = post(route('product.store'), $data);
    //Assert
    $request->assertInvalid($error);
})->with([
            'title:required' => [
                ['title' => ''],
                ['title' => 'required']
            ],
            'title:max:255' => [
                ['title' => str_repeat('*', 300)],
                ['title' => 'The title field must not be greater than 255 characters.']
            ]
        ]);
