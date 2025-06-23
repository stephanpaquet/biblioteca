<?php

uses(\Tests\TestCase::class);

use App\Services\GoogleBooksService;
use Illuminate\Support\Facades\Http;

it('searches for books and returns results', function () {
    Http::fake([
        'https://www.googleapis.com/books/v1/volumes*' => Http::response([
            'items' => [
                [
                    'id' => 'testid1',
                    'volumeInfo' => [
                        'title' => 'Test Book',
                        'authors' => ['Author One'],
                    ],
                ],
            ],
        ], 200),
    ]);

    $service = new GoogleBooksService();
    $results = $service->searchBooks('Test Book');

    expect($results)->toBeArray();
    expect($results['items'])->toHaveCount(1);
    expect($results['items'][0]['volumeInfo']['title'])->toBe('Test Book');
});

it('returns null on API error', function () {
    Http::fake([
        'https://www.googleapis.com/books/v1/volumes*' => Http::response([], 400),
    ]);

    $service = new GoogleBooksService();
    $results = $service->searchBooks('Test Book');

    expect($results)->toBeNull();
});
