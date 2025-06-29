<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

try {
    $request = Illuminate\Http\Request::create('/register', 'GET');
    $response = $app->handle($request);

    echo "Status: " . $response->getStatusCode() . PHP_EOL;

    if ($response->getStatusCode() >= 400) {
        echo "Content: " . $response->getContent() . PHP_EOL;
    } else {
        echo "Success!" . PHP_EOL;
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . PHP_EOL;
    echo "File: " . $e->getFile() . ":" . $e->getLine() . PHP_EOL;
    echo "Trace:" . PHP_EOL . $e->getTraceAsString() . PHP_EOL;
}
