<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Services\GoogleBooksService;

class SearchController extends Controller
{
    public function index(Request $request, GoogleBooksService $googleBooksService, string $query = '')
    {
        $validated = $request->validate([
            'query' => 'nullable|string|max:255',
        ]);

        $query = $validated['query'] ?? $query;

        if ($query) {
            return Inertia::render('Search/Index', [
                'query' => $query,
                'results' => $googleBooksService->searchBooks($query),
            ]);
        }
    }
}
