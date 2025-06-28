<?php

namespace App\Http\Controllers;

use App\Services\GoogleBooksService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    private GoogleBooksService $googleBooksService;

    public function __construct(GoogleBooksService $googleBooksService)
    {
        $this->googleBooksService = $googleBooksService;
    }

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
            $books = $this->googleBooksService->searchByAuthor($query);
        }

        if (auth()->check()) {
            $userBooks = auth()->user()->books()->get();
        }

        return Inertia::render('Home', [
            'books' => $books,
            'query' => $query,
            'userBooks' => $userBooks,
            'featured' => $this->googleBooksService->getFeaturedBooks(),
            'translations' => [
                'home' => __('home'),
                'layout' => __('layout'),
            ]
        ]);
    }
}
