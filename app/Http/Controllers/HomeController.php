<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function __invoke(Request $request)
    {
        // Handle locale switching
        $locale = $request->get('locale', app()->getLocale());
        if (in_array($locale, ['en', 'fr', 'es', 'de'])) {
            app()->setLocale($locale);
        }

        // Share locale data with all Inertia responses
        Inertia::share([
            'locale' => app()->getLocale(),
            'supportedLocales' => ['en', 'fr', 'es', 'de']
        ]);

        $query = $request->get('q');
        $books = null;
        $userBooks = [];

        if ($query) {
            $books = $this->searchBooks($query);
        }

        if (auth()->check()) {
            $userBooks = auth()->user()->books()->get();
        }

        return Inertia::render('Home', [
            'books' => $books,
            'query' => $query,
            'userBooks' => $userBooks,
            'featured' => $this->getFeaturedBooks(),
            'translations' => [
                'home' => __('home'),
            ]
        ]);
    }

    private function searchBooks($query)
    {
        $apiKey = config('services.google_books.api_key');
        $baseUrl = 'https://www.googleapis.com/books/v1/volumes';
        
        try {
            $response = Http::get($baseUrl, [
                'q' => $query,
                'key' => $apiKey,
                'maxResults' => 20,
                'printType' => 'books'
            ]);

            if ($response->successful()) {
                return $response->json();
            }
        } catch (\Exception $e) {
            // Log error or handle gracefully
        }

        return ['items' => []];
    }

    private function getFeaturedBooks()
    {
        // Get some featured/popular books for the home page
        $featuredQuery = 'bestsellers fiction 2024';
        return $this->searchBooks($featuredQuery);
    }
}
