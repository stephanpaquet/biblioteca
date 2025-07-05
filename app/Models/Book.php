<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'google_book_id',
        'title',
        'authors',
        'description',
        'thumbnail',
        'published_date',
        'page_count',
        'language',
        'preview_link',
    ];

    protected $casts = [
        'authors' => 'array',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_books')
            ->withPivot('status')
            ->withTimestamps();
    }
}
