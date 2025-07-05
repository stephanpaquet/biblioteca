<?php

namespace App\Http\Controllers;

use App\Actions\UserBooks;
use App\Services\GoogleBooksService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SearchController extends Controller
{
    public function index(Request $request, GoogleBooksService $googleBooksService, UserBooks $userBooks, string $query = '')
    {
        $validated = $request->validate([
            'query' => 'nullable|string|max:255',
        ]);

        $query = $validated['query'] ?? $query;

        if ($query) {
            return Inertia::render('Search/Index', [
                'query' => $query,
                'results' => $googleBooksService->searchByAuthor($query),
                'userBooks' => $userBooks->get(),
            ]);
        }
    }

    public function search(Request $request, GoogleBooksService $googleBooksService, string $query = '')
    {
        $validated = $request->validate([
            'query' => 'nullable|string|max:255',
        ]);

        $query = $validated['query'] ?? $query;

        if ($query) {
            return Inertia::render('Search/Index', [
                'query' => $query,
                'results' => $googleBooksService->searchBooks($query),
                'userBooks' => $this->userBooks->get(['user_id' => auth()->id()]),
            ]);
        }
    }
}
