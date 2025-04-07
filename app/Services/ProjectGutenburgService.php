<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\Book;
use Illuminate\Support\Facades\Log;

class ProjectGutenburgService
{
    protected $baseUrl = 'https://www.gutenberg.org';

    /**
     * Fetch a book by its Gutenberg ID and save it to the database.
     *
     * @param int $bookId
     * @return Book|null
     */
    public function fetchBook($bookId)
    {
        $url = "{$this->baseUrl}/ebooks/{$bookId}.txt.utf-8";

        // Fetch the text file
        try {
            $response = Http::get($url);

            if ($response->failed()) {
                Log::error("Failed to fetch book from Gutenberg: {$url}");
                return null; // Handle error by logging
            }

            $text = $response->body();

            // Extract metadata (title and author)
            $title = $this->extractMetadata($text, 'Title');
            $author = $this->extractMetadata($text, 'Author');

            // Clean the text (remove Gutenberg header/footer)
            $cleanText = $this->cleanText($text);

            // Save to database
            return $this->saveBook($title, $author, $cleanText);

        } catch (\Exception $e) {
            Log::error("Error fetching book from Gutenberg: {$e->getMessage()}");
            return null; // Return null if an error occurs
        }
    }

    /**
     * Extract metadata (title/author/any key) from Gutenberg text using regex.
     *
     * @param string $text
     * @param string $key
     * @return string
     */
    protected function extractMetadata($text, $key)
    {
        $pattern = "/{$key}: (.*)/";
        preg_match($pattern, $text, $matches);
        return $matches[1] ?? "Unknown {$key}";
    }

    /**
     * Remove Gutenberg header and footer from the text.
     *
     * @param string $text
     * @return string
     */
    protected function cleanText($text)
    {
        $startMarker = '*** START OF THIS PROJECT GUTENBERG EBOOK';
        $endMarker = '*** END OF THIS PROJECT GUTENBERG EBOOK';

        $startPos = strpos($text, $startMarker);
        $endPos = strpos($text, $endMarker);

        if ($startPos !== false && $endPos !== false) {
            $startPos += strlen($startMarker);
            return trim(substr($text, $startPos, $endPos - $startPos));
        }

        // Return the original text if markers not found
        return $text;
    }

    /**
     * Save the book to the database or update if it already exists.
     *
     * @param string $title
     * @param string $author
     * @param string $text
     * @return Book
     */
    protected function saveBook($title, $author, $text)
    {
        // Create or update the book entry in the database
        return Book::updateOrCreate(
            ['title' => $title, 'author' => $author],
            ['text' => $text]
        );
    }
}
