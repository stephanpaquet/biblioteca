<?php

namespace App\Http\Controllers;

use App\Actions\UserBooks;
use App\Services\GoogleBooksService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function __construct(
        private GoogleBooksService $googleBooksService,
        private UserBooks $userBooks
    ) {}

    public function __invoke(Request $request)
    {
        // Handle locale switching
        $locale = $request->get('locale', app()->getLocale());

        if (in_array($locale, ['en', 'fr', 'es', 'de'])) {
            app()->setLocale($locale);
        }

        $query = $request->get('q');
        $books = null;

        if ($query) {
            $books = $this->googleBooksService->searchBooks($query);
        }

        return Inertia::render('Home', [
            'books' => $books,
            'query' => $query,
            'userBooks' => $this->userBooks->get(['user_id' => auth()->id()]),
            'featured' => $books,
            'translations' => [
                'home' => __('home'),
                'layout' => __('layout'),
            ],
        ]);
    }
}
