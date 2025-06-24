<?php

use Illuminate\Support\Facades\Http;
use function Pest\Laravel\getJson;

it('returns a list of books from the Google Books API', function () {
    Http::fake([
        'https://www.googleapis.com/books/v1/volumes*' => Http::response([
            'items' => [
                [
                    'id' => 'testid1',
                    'volumeInfo' => [
                        'title' => 'Test Book',
                        'authors' => ['Author One'],
                        'publisher' => 'Test Publisher',
                        'publishedDate' => '2020-01-01',
                        'description' => 'A test book.',
                        'pageCount' => 123,
                        'categories' => ['Fiction'],
                        'imageLinks' => ['thumbnail' => 'http://example.com/thumb.jpg'],
                        'previewLink' => 'http://example.com/preview',
                    ],
                ],
            ],
        ], 200),
    ]);

    $response = getJson('/api/books/search?q=test');

    $response->assertOk();
    $response->assertJsonFragment([
        'id' => 'testid1',
        'title' => 'Test Book',
        'authors' => ['Author One'],
        'publisher' => 'Test Publisher',
        'publishedDate' => '2020-01-01',
        'description' => 'A test book.',
        'pageCount' => 123,
        'categories' => ['Fiction'],
        'thumbnail' => 'http://example.com/thumb.jpg',
        'previewLink' => 'http://example.com/preview',
    ]);
});

it('returns an empty array if no books are found', function () {
    Http::fake([
        'https://www.googleapis.com/books/v1/volumes*' => Http::response(['items' => []], 200),
    ]);

    $response = getJson('/api/books/search?q=notfound');

    $response->assertOk();
    $response->assertJsonCount(0, 'data');
});
