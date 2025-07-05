<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\GoogleBookResource;
use App\Services\GoogleBooksService;

/**
 * @group Google Books
 *
 * Endpoints for retrieving a single book by Google Books volume ID.
 */
class GoogleBookController extends Controller
{
    /**
     * Get a single book by Google Books volume ID.
     *
     * Retrieve detailed information for a specific book using its Google Books volume ID.
     *
     * @urlParam id string required The Google Books volume ID. Example: testid1
     *
     * @responseFile status=200 scenario="Success" responses/google-book.success.json
     *
     * @return GoogleBookResource|\Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $service = app(GoogleBooksService::class);
        $response = $service->getBook($id);

        if (! $response) {
            return response()->json(['message' => 'Book not found'], 404);
        }

        return new GoogleBookResource($response);
    }
}
