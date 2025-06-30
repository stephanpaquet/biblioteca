<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\GoogleBooksService;
use App\Http\Resources\GoogleBookResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * @group Google Books
 *
 * Endpoints for searching books using the Google Books API.
 */
class GoogleBooksController extends Controller
{
    /**
     * Search for books using the Google Books API.
     *
     * Search for books by title, author, or keyword using the Google Books API.
     *
     * @queryParam q string required The search query. Example: harry potter
     * @queryParam maxResults int The maximum number of results to return. Example: 12
     * @queryParam startIndex int The index of the first result to return (for pagination). Example: 0
     *
     * @responseFile status=200 scenario="Success" responses/google-books.success.json
     *
     * @return ResourceCollection
     */
    public function search(Request $request): ResourceCollection
    {
        $query = $request->input('q');

        $service = app(GoogleBooksService::class);
        $results = $service->searchBooks($query);
        $items = $results['items'] ?? [];
        return GoogleBookResource::collection($items);
    }
}
