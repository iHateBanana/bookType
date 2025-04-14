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
    public function practice(Book $book = null)
    {
        // If no book is provided, redirect to the typing.demo route
        if (!$book) {
            return redirect()->route('typing.demo');
        }

        // If a book is provided, render the practice page
        return Inertia::render('Typing/Practice', [
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
        return Inertia::render('Typing/SelectBook', ['books' => $books]);
    }
}
