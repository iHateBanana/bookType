<?php
namespace App\Http\Controllers;

use App\Models\Book;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

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
        $book = Book::find($bookId);

        if (!$book) {
            // If the book is not found, show the Select page with an error message
            return Inertia::render('Typing/Select', [
                'error' => 'Book not found',
                'books' => Book::all(),
            ]);
        }

        // Try to fetch book text from the download URL
        try {
            $bookText = file_get_contents($book->download_url);

            if (!$bookText) {
                throw new \Exception('Could not fetch the book text.');
            }
        } catch (\Exception $e) {
            // Log the error for debugging purposes
            Log::error('Failed to fetch book text for book ID ' . $bookId, [
                'error' => $e->getMessage(),
            ]);

            return Inertia::render('Typing/Select', [
                'error' => 'Could not load book text',
                'books' => Book::all(),
            ]);
        }

        // If everything goes well, render the Practice page
        return Inertia::render('Typing/Practice', [
            'book' => $book,
            'bookText' => $bookText,
        ]);
    }
}

