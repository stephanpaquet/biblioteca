<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ContactController;

Route::get('/', HomeController::class);
Route::get('/contact', ContactController::class);

