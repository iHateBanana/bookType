<?php
namespace App\Http\Controllers;

use App\Models\Book;
use Inertia\Inertia;

class TypingController extends Controller
{
    /**
     * Show the practice page for a selected book.
     *
     * @param int $bookId
     * @return \Inertia\Response
     */
    public function practice($bookId)
    {
        // Debugging line to check if the correct book is fetched
        \Log::info("Attempting to load book with ID: {$bookId}");

        $book = Book::find($bookId);

        if (!$book) {
            \Log::info("Book with ID {$bookId} not found.");
            return Inertia::render('Typing/Select', [
                'error' => 'Book not found',
                'books' => Book::all()
            ]);
        }

        // If book is found, proceed with fetching the text
        \Log::info("Book with ID {$bookId} found: {$book->title}");

        $bookText = @file_get_contents($book->download_url);

        return Inertia::render('Practice', [
            'book' => $book,
            'bookText' => $bookText,
        ]);
    }

}

