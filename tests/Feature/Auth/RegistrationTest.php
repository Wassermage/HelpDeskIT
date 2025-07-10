<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

test('registration screen can be rendered', function () {
    if (!Route::has('register')) {
        $this->markTestSkipped('Registration route is disabled.');
    }
    
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register', function () {
    if (!Route::has('register')) {
        $this->markTestSkipped('Registration route is disabled.');
    }

    $response = Volt::test('auth.register')
        ->set('name', 'Test User')
        ->set('email', 'test@example.com')
        ->set('password', 'password')
        ->set('password_confirmation', 'password')
        ->call('register');

    $response
        ->assertHasNoErrors()
        ->assertRedirect(route('dashboard', absolute: false));

    $this->assertAuthenticated();
});