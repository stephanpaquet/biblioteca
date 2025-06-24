<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ContactController;

Route::get('/', HomeController::class);
Route::get('/contact', ContactController::class);
Route::get('/books/{id}', function ($id) {
    return Inertia::render('Book', ['id' => $id]);
})->name('book-detail');

// Route::get('/books/search', function (\Illuminate\Http\Request $request) {
//     $query = $request->input('q', 'caroline soucy pol polaire');
//     $service = resolve(App\Services\GoogleBooksService::class);
//     $results = $service->searchBooks($query);
//     foreach ($results['items'] ?? [] as $book) {
//         dump($book);
//     }
// })->name('books.search');
