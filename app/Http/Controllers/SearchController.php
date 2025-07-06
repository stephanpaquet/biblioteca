<?php

namespace App\Http\Controllers;

use App\Actions\UserBooks;
use App\Services\GoogleBooksService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SearchController extends Controller
{
    public function index(Request $request, GoogleBooksService $googleBooksService, UserBooks $userBooks)
    {
        $validated = $request->validate([
            'q' => 'required|string|max:255',
            'type' => 'nullable|string|in:general,isbn,title,author,publisher,subject,description',
            'language' => 'nullable|string|max:10',
            'publishedAfter' => 'nullable|integer|min:1000|max:2024',
            'publishedBefore' => 'nullable|integer|min:1000|max:2024',
            'printType' => 'nullable|string|in:books,magazines',
            'orderBy' => 'nullable|string|in:relevance,newest,oldest',
            'maxResults' => 'nullable|integer|in:10,20,40',
        ]);

        $query = $validated['q'];
        $searchType = $validated['type'] ?? 'general';

        // Build search parameters for Google Books API
        $searchParams = [
            'query' => $query,
            'type' => $searchType,
            'language' => $validated['language'] ?? null,
            'publishedAfter' => $validated['publishedAfter'] ?? null,
            'publishedBefore' => $validated['publishedBefore'] ?? null,
            'printType' => $validated['printType'] ?? null,
            'orderBy' => $validated['orderBy'] ?? 'relevance',
            'maxResults' => $validated['maxResults'] ?? 20,
        ];

        // Remove null values
        $searchParams = array_filter($searchParams, function ($value) {
            return $value !== null && $value !== '';
        });

        try {
            $results = $googleBooksService->advancedSearch($searchParams);

            return Inertia::render('Search/Index', [
                'query' => $query,
                'searchType' => $searchType,
                'filters' => $validated,
                'results' => $results,
                'userBooks' => $userBooks->get(),
                'totalResults' => $results['totalItems'] ?? 0,
            ]);
        } catch (\Exception $e) {
            return Inertia::render('Search/Index', [
                'query' => $query,
                'searchType' => $searchType,
                'filters' => $validated,
                'results' => null,
                'userBooks' => $userBooks->get(),
                'error' => 'Search failed. Please try again.',
                'totalResults' => 0,
            ]);
        }
    }

    /**
     * Legacy search method - kept for backward compatibility
     *
     * @deprecated Use index() method instead
     */
    public function search(Request $request, GoogleBooksService $googleBooksService, UserBooks $userBooks)
    {
        return $this->index($request, $googleBooksService, $userBooks);
    }
}
