<?php

namespace App\Http\Controllers;

use App\Actions\GetUserBooks;
use App\Services\GoogleBooksService;
use App\Traits\HasTranslations;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

class HomeController extends Controller
{
    use HasTranslations;

    public function __construct(
        private GoogleBooksService $googleBooksService,
        private GetUserBooks $getUserBooks
    ) {}

    public function __invoke(Request $request)
    {
        // Handle locale switching
        $locale = $request->get('locale', app()->getLocale());

        if (in_array($locale, ['en', 'fr'])) {
            app()->setLocale($locale);
            Session::put('locale', $locale);
        }

        $query = $request->get('q');
        $books = null;

        if ($query) {
            $books = $this->googleBooksService->searchBooks($query);
        }

        return Inertia::render('Home', [
            'books' => $books,
            'query' => $query,
            'userBooks' => $this->getUserBooks->handle(),
            'featured' => $books,
        ]);
    }
}
