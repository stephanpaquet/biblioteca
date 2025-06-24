<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GoogleBookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        $info = $this['volumeInfo'] ?? [];
        return [
            'id' => $this['id'] ?? null,
            'title' => $info['title'] ?? null,
            'authors' => $info['authors'] ?? [],
            'publisher' => $info['publisher'] ?? null,
            'publishedDate' => $info['publishedDate'] ?? null,
            'description' => $info['description'] ?? null,
            'pageCount' => $info['pageCount'] ?? null,
            'categories' => $info['categories'] ?? [],
            'thumbnail' => $info['imageLinks']['thumbnail'] ?? null,
            'previewLink' => $info['previewLink'] ?? null,
        ];
    }
}
