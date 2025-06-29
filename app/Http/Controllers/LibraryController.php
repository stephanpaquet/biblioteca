<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class LibraryController extends Controller
{
    public function index(Request $request)
    {
        try {
            $books = $request->user()->books()
                ->orderBy('user_books.created_at', 'desc')
                ->get();

            return Inertia::render('Library', [
                'books' => $books,
                'translations' => [
                    'library' => __('library'),
                    'layout' => __('layout'),
                ]

            ]);
        } catch (\Exception $e) {
            Log::error('Library index error: ' . $e->getMessage());

            return Inertia::render('Library', [
                'books' => collect([]),
                'error' => 'Unable to load library'
            ]);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'google_book_id' => 'required|string',
            'title' => 'required|string',
            'authors' => 'nullable|array',
            'description' => 'nullable|string',
            'thumbnail' => 'nullable|string',
            'published_date' => 'nullable|string',
            'page_count' => 'nullable|integer',
            'language' => 'nullable|string',
            'preview_link' => 'nullable|string',
            'status' => 'nullable|in:want_to_read,reading,read'
        ]);

        $book = Book::firstOrCreate(
            ['google_book_id' => $request->google_book_id],
            $request->only([
                'title', 'authors', 'description', 'thumbnail',
                'published_date', 'page_count', 'language', 'preview_link'
            ])
        );

        $user = $request->user();

        if ($user->books()->where('book_id', $book->id)->exists()) {
            return response()->json(['message' => 'Book already in library'], 409);
        }

        $user->books()->attach($book->id, [
            'status' => $request->status ?? 'want_to_read'
        ]);

        return response()->json(['message' => 'Book added to library']);
    }

    public function destroy(Request $request, $bookId)
    {
        $request->user()->books()->detach($bookId);

        return response()->json(['message' => 'Book removed from library']);
    }

    public function updateStatus(Request $request, $bookId)
    {
        $request->validate([
            'status' => 'required|in:want_to_read,reading,read'
        ]);

        $request->user()->books()->updateExistingPivot($bookId, [
            'status' => $request->status
        ]);

        return response()->json(['message' => 'Status updated']);
    }
}
