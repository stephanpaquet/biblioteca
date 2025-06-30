<?php

use App\Models\User;
use App\Models\Book;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Inertia\Testing\AssertableInertia as Assert;

uses(DatabaseMigrations::class);

beforeEach(function () {
    $this->user = User::factory()->create([
        'email_verified_at' => now()
    ]);

    $this->book = Book::factory()->create([
        'google_book_id' => 'test-book-123',
        'title' => 'Test Book',
        'authors' => ['Test Author'],
    ]);
});

describe('LibraryController Index', function () {
    it('displays library page for authenticated user', function () {
        $response = $this->actingAs($this->user)
            ->get('/library');

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('Library')
                ->has('books')
                ->has('user')
            );
    });

    it('redirects unauthenticated users to login', function () {
        $response = $this->get('/library');

        $response->assertRedirect(route('login'));
    });

    it('displays user books in correct order', function () {
        // Create books for user
        $book1 = Book::factory()->create(['title' => 'First Book']);
        $book2 = Book::factory()->create(['title' => 'Second Book']);

        $this->user->books()->attach($book1->id, ['created_at' => now()->subDay()]);
        $this->user->books()->attach($book2->id, ['created_at' => now()]);

        $response = $this->actingAs($this->user)
            ->get('/library');

        $response->assertInertia(fn (Assert $page) => $page
            ->where('books.0.title', 'Second Book')
            ->where('books.1.title', 'First Book')
        );
    });
});

describe('LibraryController Store', function () {
    it('adds new book to library successfully', function () {
        $bookData = [
            'google_book_id' => 'new-book-456',
            'title' => 'New Book',
            'authors' => ['New Author'],
            'description' => 'A great book',
            'thumbnail' => 'http://example.com/thumbnail.jpg',
            'published_date' => '2023-01-01',
            'page_count' => 300,
            'language' => 'en',
            'preview_link' => 'http://example.com/preview',
            'status' => 'want_to_read'
        ];

        $response = $this->actingAs($this->user)
            ->postJson('/api/library', $bookData);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Book added to library']);

        $this->assertDatabaseHas('books', [
            'google_book_id' => 'new-book-456',
            'title' => 'New Book'
        ]);

        $this->assertDatabaseHas('user_books', [
            'user_id' => $this->user->id,
            'status' => 'want_to_read'
        ]);
    });

    it('prevents duplicate books in library', function () {
        $this->user->books()->attach($this->book->id);

        $bookData = [
            'google_book_id' => $this->book->google_book_id,
            'title' => $this->book->title,
            'authors' => $this->book->authors,
        ];

        $response = $this->actingAs($this->user)
            ->postJson('/api/library', $bookData);

        $response->assertStatus(409)
            ->assertJson(['message' => 'Book already in library']);
    });

    it('validates required fields', function () {
        $response = $this->actingAs($this->user)
            ->postJson('/api/library', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['google_book_id', 'title']);
    });

    it('validates status field', function () {
        $bookData = [
            'google_book_id' => 'test-book-789',
            'title' => 'Test Book',
            'status' => 'invalid_status'
        ];

        $response = $this->actingAs($this->user)
            ->postJson('/api/library', $bookData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['status']);
    });

    it('requires authentication', function () {
        $bookData = [
            'google_book_id' => 'test-book-unauthorized',
            'title' => 'Test Book'
        ];

        $response = $this->postJson('/api/library', $bookData);

        $response->assertStatus(401);
    });

    it('uses existing book if google_book_id already exists', function () {
        $existingBook = Book::factory()->create([
            'google_book_id' => 'existing-book-123'
        ]);

        $bookData = [
            'google_book_id' => 'existing-book-123',
            'title' => 'Different Title',
            'authors' => ['Different Author']
        ];

        $response = $this->actingAs($this->user)
            ->postJson('/api/library', $bookData);

        $response->assertStatus(200);

        // Should not create new book, should use existing
        $this->assertEquals(1, Book::where('google_book_id', 'existing-book-123')->count());
        $this->assertTrue($this->user->books()->where('book_id', $existingBook->id)->exists());
    });
});

describe('LibraryController Destroy', function () {
    it('removes book from user library', function () {
        $this->user->books()->attach($this->book->id);

        $response = $this->actingAs($this->user)
            ->deleteJson("/api/library/{$this->book->id}");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Book removed from library']);

        $this->assertDatabaseMissing('user_books', [
            'user_id' => $this->user->id,
            'book_id' => $this->book->id
        ]);
    });

    it('handles removing non-existent book gracefully', function () {
        $response = $this->actingAs($this->user)
            ->deleteJson("/api/library/999");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Book removed from library']);
    });

    it('requires authentication', function () {
        $response = $this->deleteJson("/api/library/{$this->book->id}");

        $response->assertStatus(401);
    });
});

describe('LibraryController UpdateStatus', function () {
    beforeEach(function () {
        $this->user->books()->attach($this->book->id, ['status' => 'want_to_read']);
    });

    it('updates book status successfully', function () {
        $response = $this->actingAs($this->user)
            ->patchJson("/api/library/{$this->book->id}/status", [
                'status' => 'reading'
            ]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Status updated']);

        $this->assertDatabaseHas('user_books', [
            'user_id' => $this->user->id,
            'book_id' => $this->book->id,
            'status' => 'reading'
        ]);
    });

    it('validates status field', function () {
        $response = $this->actingAs($this->user)
            ->patchJson("/api/library/{$this->book->id}/status", [
                'status' => 'invalid_status'
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['status']);
    });

    it('requires status field', function () {
        $response = $this->actingAs($this->user)
            ->patchJson("/api/library/{$this->book->id}/status", []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['status']);
    });

    it('requires authentication', function () {
        $response = $this->patchJson("/api/library/{$this->book->id}/status", [
            'status' => 'reading'
        ]);

        $response->assertStatus(401);
    });

    it('allows all valid status values', function () {
        $validStatuses = ['want_to_read', 'reading', 'read'];

        foreach ($validStatuses as $status) {
            $response = $this->actingAs($this->user)
                ->patchJson("/api/library/{$this->book->id}/status", [
                    'status' => $status
                ]);

            $response->assertStatus(200);

            $this->assertDatabaseHas('user_books', [
                'user_id' => $this->user->id,
                'book_id' => $this->book->id,
                'status' => $status
            ]);
        }
    });
});
