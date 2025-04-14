<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class BooksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Number of books to fetch
        $numBooks = 10;

        for ($i = 1; $i <= $numBooks; $i++) {
            // Fetch book data from Project Gutenberg API
            $response = Http::get("https://www.gutenberg.org/ebooks/{$i}.json");

            // Log the response to inspect its structure
            if ($response->successful()) {
                \Log::info("Gutenberg API Response for Book {$i}: ", $response->json());
            } else {
                \Log::warning("Failed to fetch data for book ID {$i}");
            }

            // If the response is successful and contains book data
            if ($response->successful()) {
                $book = $response->json();

                // Ensure the book has the necessary attributes before inserting
                if (isset($book['title'], $book['author'], $book['id'])) {
                    DB::table('books')->insert([
                        'title' => $book['title'],
                        'author' => $book['author'],
                        'cover_url' => $this->getCoverUrl($book['id']),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                } else {
                    \Log::warning("Missing required data for book ID {$i}: ", $book);
                }
            }
        }
    }



    /**
     * Get the cover URL for a given book from Gutenberg.
     *
     * @param  int  $bookId
     * @return string
     */
    private function getCoverUrl($bookId)
    {
        // Construct the cover image URL based on the book ID (example URL format, you might need to adjust)
        return "https://www.gutenberg.org/cache/epub/{$bookId}/pg{$bookId}.cover.medium.jpg";
    }
}
