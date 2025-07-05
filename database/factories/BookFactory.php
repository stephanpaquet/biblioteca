<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    public function definition(): array
    {
        return [
            'google_book_id' => 'GB_'.$this->faker->unique()->randomNumber(8),
            'title' => $this->faker->words(3, true),
            'authors' => [$this->faker->name(), $this->faker->name()],
            'description' => $this->faker->paragraph(),
            'thumbnail' => 'https://books.google.com/books/content?id=test&printsec=frontcover&img=1&zoom=1',
            'published_date' => $this->faker->date('Y-m-d', '2023-12-31'),
            'page_count' => $this->faker->numberBetween(50, 800),
            'language' => $this->faker->randomElement(['en', 'fr', 'es', 'de']),
            'preview_link' => 'https://books.google.com/books?id=test&pg=PP1&dq=test',
        ];
    }

    public function withGoogleBookId(string $googleBookId): static
    {
        return $this->state(fn (array $attributes) => [
            'google_book_id' => $googleBookId,
        ]);
    }

    public function withTitle(string $title): static
    {
        return $this->state(fn (array $attributes) => [
            'title' => $title,
        ]);
    }
}
