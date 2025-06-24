<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\GoogleBooksService;
use App\Http\Resources\GoogleBookResource;
use Illuminate\Http\Request;

class GoogleBookController extends Controller
{
    /**
     * Get a single book by Google Books volume ID.
     *
     * @group Google Books
     * @urlParam id string required The Google Books volume ID. Example: testid1
     * @responseFile status=200 scenario="Success" responses/google-book.success.json
     */
    public function show($id)
    {
        $service = app(GoogleBooksService::class);
        $response = $service->getBook($id);
        if (!$response) {
            return response()->json(['message' => 'Book not found'], 404);
        }
        return new GoogleBookResource($response);
    }
}
