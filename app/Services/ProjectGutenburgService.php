<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\Book;
use Illuminate\Support\Facades\Log;

class ProjectGutenburgService
{
    public function fetchBook($bookId)
    {
        $response = Http::get("https://gutendex.com/books/{$bookId}");

        if ($response->failed()) {
            Log::warning("Failed to fetch book metadata for ID {$bookId}");
            return null;
        }

        $data = $response->json();

        // Only allow public domain books
        if (!array_key_exists('copyright', $data) || $data['copyright'] !== false) {
            Log::info("Book ID {$bookId} is not public domain.");
            return null;
        }

        // Get download URL for plain text
        $formats = $data['formats'];
        $downloadUrl = collect($formats)
            ->filter(fn($url, $type) => str_starts_with($type, 'text/plain'))
            ->first();

        if (!$downloadUrl) {
            Log::warning("No valid text download format found for book ID {$bookId}");
            return null;
        }

        $coverUrl = $formats['image/jpeg'] ?? null;

        $book = Book::updateOrCreate(
            ['gutenberg_id' => $data['id']],
            [
                'title' => $data['title'],
                'author' => $data['authors'][0]['name'] ?? 'Unknown',
                'language' => $data['languages'][0] ?? 'en',
                'download_url' => $downloadUrl,
                'cover_url' => $coverUrl,
            ]
        );

        Log::info("Book saved: {$book->title}");

        return $book;
    }
}
