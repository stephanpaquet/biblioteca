<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('query');
        
        // Assuming you have a Book model and it's searchable
        $books = Book::search($query)->get();

        $userBooks = [];
        if (auth()->check()) {
            $userBooks = auth()->user()->books()->get();
        }

        return Inertia::render('SearchResults', [
            'books' => $books,
            'query' => $query,
            'userBooks' => $userBooks
        ]);
    }
}