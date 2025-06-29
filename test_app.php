<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

// Test the Fortify view response
try {
    $response = $app->make(Laravel\Fortify\Contracts\RegisterViewResponse::class);
    echo "✅ RegisterViewResponse contract is properly bound\n";

    $response = $app->make(Laravel\Fortify\Contracts\LoginViewResponse::class);
    echo "✅ LoginViewResponse contract is properly bound\n";

    $response = $app->make(Laravel\Fortify\Contracts\LoginResponse::class);
    echo "✅ LoginResponse contract is properly bound\n";

    $response = $app->make(Laravel\Fortify\Contracts\RegisterResponse::class);
    echo "✅ RegisterResponse contract is properly bound\n";

    echo "\n🎉 All Fortify contracts are properly bound! The binding resolution error should be fixed.\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
