<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\GoogleBooksController;
use App\Http\Controllers\Api\GoogleBookController;

Route::get('books/search', [GoogleBooksController::class, 'search']);
Route::get('books/{id}', [GoogleBookController::class, 'show']);
