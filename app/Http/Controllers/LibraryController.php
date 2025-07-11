<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Services\GoogleBooksService;
use App\Traits\HasTranslations;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class LibraryController extends Controller
{
    use HasTranslations;

    protected $googleBooksService;

    public function __construct(GoogleBooksService $googleBooksService)
    {
        $this->googleBooksService = $googleBooksService;
    }

    public function index(Request $request)
    {
        try {
            $books = $request->user()->books()
                ->orderBy('user_books.created_at', 'desc')
                ->get();

            return Inertia::render('Library', [
                'books' => $books,
                // Translations are now global - no need to pass them
            ]);
        } catch (\Exception $e) {
            Log::error('Library index error: '.$e->getMessage());

            return Inertia::render('Library', [
                'books' => collect([]),
                'error' => 'Unable to load library',
            ]);
        }
    }

    public function store(Request $request)
    {
        // // Check if user has permission to add books
        // if (!$request->user()->can('add books')) {
        //     return response()->json(['message' => 'You do not have permission to add books.'], 403);
        // }

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
            'status' => 'nullable|in:want_to_read,reading,read',
        ]);

        $book = Book::firstOrCreate(
            ['google_book_id' => $request->google_book_id],
            $request->only([
                'title', 'authors', 'description', 'thumbnail',
                'published_date', 'page_count', 'language', 'preview_link',
            ])
        );

        $user = $request->user();

        if ($user->books()->where('book_id', $book->id)->exists()) {
            return response()->json(['message' => 'Book already in library'], 409);
        }

        $user->books()->attach($book->id, [
            'status' => $request->status ?? 'want_to_read',
        ]);

        return response()->json(['message' => 'Book added to library']);
    }

    public function destroy(Request $request, $googleBookId)
    {
        $bookId = Book::where('google_book_id', $googleBookId)->value('id');
        $request->user()->books()->detach($bookId);

        return response()->json(['message' => 'Book removed from library']);
    }

    public function updateStatus(Request $request, $bookId)
    {
        // Check if user has permission to update book status
        if (! $request->user()->can('update book status')) {
            return response()->json(['message' => 'You do not have permission to update book status.'], 403);
        }

        $request->validate([
            'status' => 'required|in:want_to_read,reading,read',
        ]);

        $request->user()->books()->updateExistingPivot($bookId, [
            'status' => $request->status,
        ]);

        return response()->json(['message' => 'Status updated']);
    }

    public function sync(Request $request, $bookId)
    {
        try {
            $book = Book::findOrFail($bookId);

            // Verify the book belongs to the user
            if (! $request->user()->books()->where('book_id', $bookId)->exists()) {
                return response()->json(['message' => 'Book not found in your library'], 404);
            }

            // Fetch updated data from Google Books API
            $googleBookData = $this->googleBooksService->getBook($book->google_book_id);

            if (! $googleBookData) {
                return response()->json(['message' => 'Book not found in Google Books'], 404);
            }

            // Update the book with new data from Google Books
            $book->update([
                'title' => $googleBookData['volumeInfo']['title'] ?? $book->title,
                'authors' => $googleBookData['volumeInfo']['authors'] ?? $book->authors,
                'description' => $googleBookData['volumeInfo']['description'] ?? $book->description,
                'thumbnail' => $googleBookData['volumeInfo']['imageLinks']['thumbnail'] ?? $book->thumbnail,
                'published_date' => $googleBookData['volumeInfo']['publishedDate'] ?? $book->published_date,
                'page_count' => $googleBookData['volumeInfo']['pageCount'] ?? $book->page_count,
                'language' => $googleBookData['volumeInfo']['language'] ?? $book->language,
                'preview_link' => $googleBookData['volumeInfo']['previewLink'] ?? $book->preview_link,
                'publisher' => $googleBookData['volumeInfo']['publisher'] ?? $book->publisher,
                'categories' => $googleBookData['volumeInfo']['categories'] ?? $book->categories,
                'isbn' => $this->extractIsbn($googleBookData['volumeInfo']['industryIdentifiers'] ?? []) ?? $book->isbn,
            ]);

            return response()->json([
                'message' => 'Book information synced successfully',
                'book' => $book->fresh(),
            ]);

        } catch (\Exception $e) {
            Log::error('Book sync error: '.$e->getMessage());

            return response()->json(['message' => 'Failed to sync book information'], 500);
        }
    }

    /**
     * Extract ISBN from Google Books industry identifiers
     */
    private function extractIsbn(array $identifiers): ?string
    {
        foreach ($identifiers as $identifier) {
            if (in_array($identifier['type'], ['ISBN_10', 'ISBN_13'])) {
                return $identifier['identifier'];
            }
        }

        return null;
    }
}
