<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LibraryController;

Route::get('/', HomeController::class);
Route::get('/dashboard', DashboardController::class);
Route::get('/books/{id}', function ($id) {
    return Inertia::render('Book', ['id' => $id]);
})->name('book-detail');

Route::get('/login', function () {
    return Inertia::render('Login');
})->name('login');

Route::get('/register', function () {
    return Inertia::render('Register');
})->name('register');

Route::get('/password/reset', function () {
    return Inertia::render('PasswordReset');
})->name('password.request');

// Temporary debug route to check auth status
Route::get('/debug-auth', function () {
    return response()->json([
        'authenticated' => auth()->check(),
        'user' => auth()->user(),
        'session_id' => session()->getId(),
        'guards' => config('auth.guards')
    ]);
});

Route::middleware(['auth'])->group(function () {
    Route::get('/library', [LibraryController::class, 'index'])->name('library');
    Route::post('/api/library', [LibraryController::class, 'store'])->name('library.store');
    Route::delete('/api/library/{book}', [LibraryController::class, 'destroy'])->name('library.destroy');
    Route::patch('/api/library/{book}/status', [LibraryController::class, 'updateStatus'])->name('library.update-status');
});

