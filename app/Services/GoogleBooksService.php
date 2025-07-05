<?php

// https://developers.google.com/books/docs/v1/reference?hl=fr

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GoogleBooksService
{
    private string $baseUrl;

    private ?string $apiKey;

    private int $cacheMinutes;

    private bool $cacheEnabled;

    public function __construct()
    {
        $this->baseUrl = 'https://www.googleapis.com/books/v1/volumes';
        $this->apiKey = config('services.google_books.api_key');
        $this->cacheMinutes = config('services.google_books.cache_minutes', 60);
        $this->cacheEnabled = config('services.google_books.cache_enabled', false);
    }

    /**
     * Search for books using the Google Books API
     */
    public function searchBooks(string $query, array $options = []): array
    {
        if (! $this->cacheEnabled) {
            return $this->performSearchRequest($query, $options);
        }

        $cacheKey = $this->generateCacheKey('search', $query, $options);

        return Cache::remember($cacheKey, $this->cacheMinutes * 60, function () use ($query, $options) {
            return $this->performSearchRequest($query, $options);
        });
    }

    /**
     * Get a specific book by its Google Books ID
     */
    public function getBook(string $bookId): ?array
    {
        if (! $this->cacheEnabled) {
            return $this->performBookRequest($bookId);
        }

        $cacheKey = $this->generateCacheKey('book', $bookId);

        return Cache::remember($cacheKey, $this->cacheMinutes * 60 * 24, function () use ($bookId) {
            return $this->performBookRequest($bookId);
        });
    }

    /**
     * Get featured/bestseller books
     */
    public function getFeaturedBooks(string $category = 'bestsellers fiction 2024'): array
    {
        if (! $this->cacheEnabled) {
            return $this->performSearchRequest($category, ['maxResults' => 12]);
        }

        $cacheKey = $this->generateCacheKey('featured', $category);

        return Cache::remember($cacheKey, $this->cacheMinutes * 60 * 6, function () use ($category) {
            return $this->performSearchRequest($category, ['maxResults' => 12]);
        });
    }

    /**
     * Search books by title
     */
    public function searchByTitle(string $title, array $options = []): array
    {
        $query = "intitle:\"{$title}\"";

        return $this->searchBooks($query, $options);
    }

    /**
     * Search books by author
     */
    public function searchByAuthor(string $author, array $options = []): array
    {
        $query = "inauthor:\"{$author}\"";

        return $this->searchBooks($query, $options);
    }

    /**
     * Search books by subject/genre
     */
    public function searchBySubject(string $subject, array $options = []): array
    {
        $query = "subject:{$subject}";

        return $this->searchBooks($query, $options);
    }

    /**
     * Search books by publisher
     */
    public function searchByPublisher(string $publisher, array $options = []): array
    {
        $query = "inpublisher:{$publisher}";

        return $this->searchBooks($query, $options);
    }

    /**
     * Check if API key is configured
     */
    public function hasApiKey(): bool
    {
        return ! empty($this->apiKey);
    }

    /**
     * Check if caching is enabled
     */
    public function isCacheEnabled(): bool
    {
        return $this->cacheEnabled;
    }

    /**
     * Clear cache for specific query
     */
    public function clearCache(?string $query = null): bool
    {
        if ($query) {
            $cacheKey = $this->generateCacheKey('search', $query);

            return Cache::forget($cacheKey);
        }

        // Clear all Google Books cache
        return Cache::flush();
    }

    /**
     * Get API key parameter if available
     */
    private function getApiKeyParam(): array
    {
        return $this->apiKey ? ['key' => $this->apiKey] : [];
    }

    /**
     * Perform the actual search request to Google Books API
     */
    private function performSearchRequest(string $query, array $options = []): array
    {
        $params = array_merge([
            'q' => $query,
            'maxResults' => $options['maxResults'] ?? 20,
            'printType' => $options['printType'] ?? 'books',
            'startIndex' => $options['startIndex'] ?? 0,
        ], $this->getApiKeyParam());

        try {
            $response = Http::timeout(10)->get($this->baseUrl, $params);

            if ($response->successful()) {
                return $response->json();
            }

            Log::warning('Google Books API request failed', [
                'status' => $response->status(),
                'query' => $query,
                'response' => $response->body(),
            ]);

        } catch (\Exception $e) {
            Log::error('Google Books API exception', [
                'message' => $e->getMessage(),
                'query' => $query,
            ]);
        }

        return ['items' => [], 'totalItems' => 0];
    }

    /**
     * Perform the actual book request to Google Books API
     */
    private function performBookRequest(string $bookId): ?array
    {
        $params = $this->getApiKeyParam();

        try {
            $response = Http::timeout(10)->get("{$this->baseUrl}/{$bookId}", $params);

            if ($response->successful()) {
                return $response->json();
            }

            Log::warning('Google Books API book fetch failed', [
                'status' => $response->status(),
                'bookId' => $bookId,
            ]);

        } catch (\Exception $e) {
            Log::error('Google Books API book fetch exception', [
                'message' => $e->getMessage(),
                'bookId' => $bookId,
            ]);
        }

        return null;
    }

    /**
     * Generate a cache key for the request
     */
    private function generateCacheKey(string $type, string $identifier, array $options = []): string
    {
        $keyData = [
            'type' => $type,
            'identifier' => $identifier,
            'options' => $options,
            'api_key_present' => $this->hasApiKey(),
        ];

        return 'google_books:'.md5(json_encode($keyData));
    }
}
