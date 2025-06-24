<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\GoogleBooksController;

Route::get('books/search', [GoogleBooksController::class, 'search']);
