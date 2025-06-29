<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

// Use the kernel to handle a fake request
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Create a fake login request
$request = Illuminate\Http\Request::create('/debug-auth', 'GET');
$response = $kernel->handle($request);

echo "Status: " . $response->getStatusCode() . "\n";
echo "Content: " . $response->getContent() . "\n";

$kernel->terminate($request, $response);
