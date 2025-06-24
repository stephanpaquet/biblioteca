<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

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
        $cacheKey = 'google_books_search_' . md5($query . '_' . $maxResults);
        return Cache::remember($cacheKey, 60, function () use ($query, $maxResults) {
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
        });
    }

    /**
     * Get a single book by its Google Books volume ID.
     *
     * @param string $id
     * @return array|null
     */
    public function getBook(string $id): ?array
    {
        $cacheKey = 'google_books_book_' . $id;
        return Cache::remember($cacheKey, 60, function () use ($id) {
            $url = $this->baseUrl . '/' . urlencode($id);
            $params = [];
            if ($this->apiKey) {
                $params['key'] = $this->apiKey;
            }
            $response = \Illuminate\Support\Facades\Http::get($url, $params);
            if ($response->successful()) {
                return $response->json();
            }
            return null;
        });
    }
}
