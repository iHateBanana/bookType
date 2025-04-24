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
        // Fetch book metadata from the service
        $bookData = $this->gutenbergService->fetchBook($bookId);

        if (!$bookData) {
            Log::error("Failed to fetch book with ID: {$bookId} from Gutenberg.");
            return redirect()->back()->with('error', 'Failed to fetch book from Project Gutenberg.');
        }

        // Check if 'formats' is set and is an array
        if (isset($bookData['formats']) && is_array($bookData['formats'])) {
            // Find a plain text URL (non-zip)
            $plainTextUrl = null;

            foreach ($bookData['formats'] as $format => $url) {
                if (str_contains($format, 'text/plain') && !str_contains($url, '.zip')) {
                    $plainTextUrl = $url;
                    break;
                }
            }

            $text = null;
            if ($plainTextUrl) {
                try {
                    $text = file_get_contents($plainTextUrl);
                } catch (\Exception $e) {
                    Log::error("Failed to fetch text from URL: {$plainTextUrl}");
                }
            }

            // If the book text was fetched successfully
            if ($text) {
                // Optionally save the book and its content to your database
                $book = Book::create([
                    'title' => $bookData['title'],
                    'author' => $bookData['author'],
                    'text' => $text,
                    'download_url' => $plainTextUrl,
                ]);

                // Redirect to the typing practice page with the book ID and text
                return redirect()->route('typing.practice', ['book' => $book->id]);
            }

            // Handle case where no text was retrieved
            Log::error("Text not found for book ID: {$bookId}");
            return redirect()->back()->with('error', 'Failed to fetch the text for the book.');
        } else {
            // Handle the case where 'formats' is missing or invalid
            Log::error("Formats not found or invalid for book ID: {$bookId}");
            return redirect()->back()->with('error', 'Failed to find book formats.');
        }
    }

}
