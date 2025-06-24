<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\GoogleBooksService;
use App\Http\Resources\GoogleBookResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class GoogleBooksController extends Controller
{
    /**
     * Search for books using the Google Books API.
     *
     * @group Google Books
     * @queryParam q string required The search query. Example: harry potter
     * @responseFile status=200 scenario="Success" responses/google-books.success.json
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
