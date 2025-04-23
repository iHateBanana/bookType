<?php
namespace App\Http\Controllers;

use App\Models\Book;
use Inertia\Inertia;

class TypingController extends Controller
{
    /**
     * Show the practice page.
     *
     * @param \App\Models\Book|null $book
     * @return \Inertia\Response
     */
    public function practice($bookId)
    {
        $book = Book::find($bookId);

        if (!$book) {
            // TODO FIX THIS, SHOULD NOT LINK BACK TO SELECT PAGE
            return Inertia::render('Typing/Select', [
                'error' => 'Book not found',
                'books' => Book::all() // Pass all books back to the view
            ]);
        }

        return Inertia::render('Practice', [
            'book' => $book
        ]);
    }




    /**
     * Show the demo page for typing practice.
     *
     * @return \Inertia\Response
     */
    public function demo()
    {
        // Render the demo page (you can create a separate Vue component for it)
        return Inertia::render('Typing/Demo');
    }

    public function selectBook()
    {
        $books = Book::all();
        return Inertia::render('Typing/Select', ['books' => $books]);
    }
}
