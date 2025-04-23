<?php
namespace App\Http\Controllers;
use App\Services\ProjectGutenburgService;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use App\Models\Book;

class BookController extends Controller
{
    protected $gutenbergService;

    public function __construct(ProjectGutenburgService $gutenbergService)
    {
        $this->gutenbergService = $gutenbergService;
    }


    public function index()
    {
        $books = Book::whereNotNull('title')->get(); // Filter out bad data just in case

        return Inertia::render('Books/Index', [
            'books' => $books
        ]);
    }



    public function selectBook()
    {
        $books = Book::orderBy('title')->get();

        return Inertia::render('Typing/Select', [
            'books' => $books,
        ]);
    }


    public function show(Book $book)
    {
        if (!$book) {
            return Inertia::render('Typing/Demo');
        }

        // Fetch book text based on the download URL or another attribute
        $bookText = file_get_contents($book->download_url);  // Assuming the book has a download_url

        return Inertia::render('Typing/Practice', [
            'book' => $book,
            'bookText' => $bookText,
        ]);
    }




    public function fetchFromGutenberg($bookId)
    {
        // Attempt to fetch the book from Gutenberg
        $book = $this->gutenbergService->fetchBook(1342);


        // If the book could not be fetched, log the error and redirect back with a failure message
        if (!$book) {
            Log::error("Failed to fetch book with ID: {$bookId} from Gutenberg.");
            return redirect()->back()->with('error', 'Failed to fetch book from Project Gutenberg.');
        }

        // Redirect to the typing page with the book's ID
        return redirect()->route('typing.practice', ['book' => $book->id]);
    }
}
