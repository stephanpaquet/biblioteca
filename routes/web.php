<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LibraryController;

Route::get('/', HomeController::class)->name('home');
Route::get('/dashboard', DashboardController::class)->name('dashboard');
Route::get('/books/{id}', function ($id) {
    return Inertia::render('Book', ['id' => $id]);
})->name('book-detail');

// Temporary debug route to check auth status
Route::get('/debug-auth', function () {
    return response()->json([
        'authenticated' => Auth::check(),
        'user' => Auth::user(),
        'session_id' => session()->getId(),
        'guards' => config('auth.guards'),
        'csrf_token' => csrf_token(),
        'middleware_applied' => request()->hasHeader('X-Inertia'),
    ]);
});

// Test the Inertia auth shared data
Route::get('/debug-inertia', function () {
    $handleInertiaRequests = new \App\Http\Middleware\HandleInertiaRequests();
    $sharedData = $handleInertiaRequests->share(request());

    return response()->json([
        'shared_data' => $sharedData,
        'auth_check' => Auth::check(),
        'user' => Auth::user(),
    ]);
});

// CSRF cookie route for API documentation
Route::get('/csrf-cookie', function () {
    return response()->json(['csrf_token' => csrf_token()]);
});

// Test CSRF token endpoint
Route::get('/test-csrf', function () {
    return response()->json([
        'csrf_token' => csrf_token(),
        'session_id' => session()->getId()
    ]);
});

Route::middleware(['auth'])->group(function () {
    Route::get('/library', [LibraryController::class, 'index'])->name('library');
    Route::post('/library', [LibraryController::class, 'store'])->name('library.store');
    Route::delete('/library/{book}', [LibraryController::class, 'destroy'])->name('library.destroy');
    Route::patch('/library/{book}/status', [LibraryController::class, 'updateStatus'])->name('library.update-status');

    // API endpoint to get current user info
    Route::get('/api/user', function () {
        return response()->json(Auth::user());
    })->name('api.user');
});

