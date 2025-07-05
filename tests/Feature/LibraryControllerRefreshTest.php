<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

test('library displays correctly with fresh database', function () {
    $user = User::factory()->create(['email_verified_at' => now()]);

    $response = $this->actingAs($user)->get('/library');

    $response->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('Library')
            ->has('books')
            ->has('user')
        );
});
