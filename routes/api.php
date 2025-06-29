<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\GoogleBooksController;
use App\Http\Controllers\Api\GoogleBookController;

/**
 * API Routes for Google Books integration
 *
 * @group Google Books
 *
 * @route GET api/books/search Search for books
 * @route GET api/books/{id} Get book details by ID
 */
Route::get('books/search', [GoogleBooksController::class, 'search'])->name('api.books.search');
Route::get('books/{id}', [GoogleBookController::class, 'show'])->name('api.books.show');

// Additional API routes that need authentication can be added here
Route::middleware(['web', 'auth'])->group(function () {
    // Add authenticated API routes here if needed
});
