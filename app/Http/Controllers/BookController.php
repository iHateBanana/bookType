<?php
namespace App\Http\Controllers;

use App\Services\ProjectGutenburgService;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class BookController extends Controller
{
    protected $gutenbergService;

    public function __construct(ProjectGutenburgService $gutenbergService)
    {
        $this->gutenbergService = $gutenbergService;
    }

    public function index()
    {
        $books = \App\Models\Book::all();

        return Inertia::render('Books/Index', [
            'books' => $books]);
    }

    public function fetchFromGutenberg($bookId)
    {
        // Attempt to fetch the book from Gutenberg
        $book = $this->gutenbergService->fetchBook($bookId);

        // If the book could not be fetched, log the error and redirect back with a failure message
        if (!$book) {
            Log::error("Failed to fetch book with ID: {$bookId} from Gutenberg.");
            return redirect()->back()->with('error', 'Failed to fetch book from Project Gutenberg.');
        }

        // Redirect to the typing page with the book's ID
        return redirect()->route('typing.practice', ['book' => $book->id]);
    }
}
