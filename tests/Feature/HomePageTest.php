<?php

use function Pest\Laravel\get;

it(' display the home page returns inertia Home view', function () {
    $response = get('/');
    $response->assertInertia(fn ($page) => $page->component('Home'));
    $response->assertOk();
});
