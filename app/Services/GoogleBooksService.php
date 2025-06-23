<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GoogleBooksService
{
    protected string $baseUrl = 'https://www.googleapis.com/books/v1/volumes';
    protected ?string $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.google_books.api_key');
    }

    /**
     * Search for books using the Google Books API.
     *
     * @param string $query
     * @param int $maxResults
     * @return array|null
     */
    public function searchBooks(string $query, int $maxResults = 10): ?array
    {
        $params = [
            'q' => $query,
            'maxResults' => $maxResults,
        ];
        if ($this->apiKey) {
            $params['key'] = $this->apiKey;
        }

        $response = Http::get($this->baseUrl, $params);

        if ($response->successful()) {
            return $response->json();
        }

        return null;
    }
}
