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

    public function searchByIsbn(string $isbn, array $options = []): array
    {
        $query = "isbn:{$isbn}";

        return $this->searchBooks($query, $options);
    }

    /**
     * Advanced search with multiple criteria and filters
     */
    public function advancedSearch(array $params): array
    {
        $query = $this->buildAdvancedQuery($params);
        $options = $this->buildAdvancedOptions($params);

        if (! $this->cacheEnabled) {
            return $this->performSearchRequest($query, $options);
        }

        $cacheKey = $this->generateCacheKey('advanced_search', $query, $options);

        return Cache::remember($cacheKey, $this->cacheMinutes * 60, function () use ($query, $options) {
            return $this->performSearchRequest($query, $options);
        });
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
     * Build the query string for advanced search
     */
    private function buildAdvancedQuery(array $params): string
    {
        $query = $params['query'];
        $type = $params['type'] ?? 'general';

        // Apply search type specific formatting
        switch ($type) {
            case 'isbn':
                return "isbn:{$query}";
            case 'title':
                return "intitle:\"{$query}\"";
            case 'author':
                return "inauthor:\"{$query}\"";
            case 'publisher':
                return "inpublisher:\"{$query}\"";
            case 'subject':
                return "subject:{$query}";
            case 'description':
                return $query; // Search in all fields including description
            case 'general':
            default:
                return $query; // General search across all fields
        }
    }

    /**
     * Build the options array for advanced search
     */
    private function buildAdvancedOptions(array $params): array
    {
        $options = [
            'maxResults' => $params['maxResults'] ?? 20,
            'printType' => $params['printType'] ?? 'books',
            'startIndex' => $params['startIndex'] ?? 0,
        ];

        // Add language restriction if specified
        if (! empty($params['language'])) {
            $options['langRestrict'] = $params['language'];
        }

        // Add order by if specified
        if (! empty($params['orderBy'])) {
            switch ($params['orderBy']) {
                case 'newest':
                    $options['orderBy'] = 'newest';

                    break;
                case 'oldest':
                    $options['orderBy'] = 'relevance'; // Google Books doesn't have "oldest", use relevance

                    break;
                case 'relevance':
                default:
                    $options['orderBy'] = 'relevance';

                    break;
            }
        }

        // Add publication date filters
        if (! empty($params['publishedAfter'])) {
            $options['publishedAfter'] = $params['publishedAfter'];
        }

        if (! empty($params['publishedBefore'])) {
            $options['publishedBefore'] = $params['publishedBefore'];
        }

        return $options;
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

        // Add optional parameters if they exist
        if (! empty($options['langRestrict'])) {
            $params['langRestrict'] = $options['langRestrict'];
        }

        if (! empty($options['orderBy'])) {
            $params['orderBy'] = $options['orderBy'];
        }

        // Handle publication date filtering in the query
        if (! empty($options['publishedAfter']) || ! empty($options['publishedBefore'])) {
            $dateFilter = $this->buildDateFilter($options['publishedAfter'] ?? null, $options['publishedBefore'] ?? null);
            if ($dateFilter) {
                $params['q'] = $query.' '.$dateFilter;
            }
        }

        Log::info(sprintf('Google Books API search request: %s', $params['q']), [
            'baseUrl' => $this->baseUrl,
            'query' => $query,
            'params' => $params,
        ]);

        try {
            $response = Http::timeout(10)->get($this->baseUrl, $params);

            if ($response->successful()) {
                $data = $response->json();

                // Limit totalItems to 300 to prevent excessive pagination
                if (isset($data['totalItems']) && $data['totalItems'] > 300) {
                    $data['totalItems'] = 300;
                }

                return $data;
            }

            Log::warning(sprintf('Google Books API request failed'), [
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
     * Build date filter for Google Books API query
     */
    private function buildDateFilter(?int $after, ?int $before): string
    {
        if ($after && $before) {
            return "publishedDate:{$after}..{$before}";
        } elseif ($after) {
            return "publishedDate:{$after}..";
        } elseif ($before) {
            return "publishedDate:..{$before}";
        }

        return '';
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
