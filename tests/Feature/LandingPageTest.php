<?php

// test('returns a successful response', function () {
//     $response = $this->get('/');

//     $response->assertStatus(200);
// });

test('guest is redirected from root to login page', function () {
    $response = $this->get('/');

    $response->assertRedirect('/login');
});

test('authenticated user is redirected from root to dashboard', function () {
    $user = \App\Models\User::factory()->create();

    $response = $this->actingAs($user)->get('/');

    $response->assertRedirect('/dashboard');
});
