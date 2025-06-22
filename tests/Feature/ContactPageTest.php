<?php

use function Pest\Laravel\get;

it('can display contact page returns inertia Contact view', function () {
    $response = get('/contact');
    $response->assertInertia(fn ($page) => $page->component('Contact'));
    $response->assertOk();
});
