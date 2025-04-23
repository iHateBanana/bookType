<?php
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\BookController;
use App\Http\Controllers\TypingController; // Ensure you import the TypingController

/*
|----------------------------------------------------------------------
| Web Routes
|----------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// Route for the typing demo, which will redirect to typing.practice with a book
Route::get('/typing', function () {
    $book = \App\Models\Book::first();
    return redirect()->route('typing.practice', ['book' => $book->id]);
})->name('typing.demo');

Route::get('/typing/{book}', [TypingController::class, 'show'])->name('typing.practice');

Route::get('/test-fetch/{id}', function ($id) {
    $book = app(\App\Services\ProjectGutenburgService::class)->fetchBook($id);
    return $book ? "Book saved: {$book->title}" : 'Failed to save book.';
});

Route::get('/typing/select', [BookController::class, 'selectBook'])->name('typing.select');


// Route to show the practice page for a selected book
Route::get('/practice/{book}', [TypingController::class, 'practice'])->name('typing.practice');
Route::get('/demo', [TypingController::class, 'demo'])->name('typing.demo');

// Route for books (displays all books)
Route::get('/books', [BookController::class, 'index'])->name('books.index');

Route::get('/select', [TypingController::class, 'selectBook'])->name('typing.select');
//Route::get('/practice/{book}', [TypingController::class, 'practice'])->name('typing.practice');

Route::get('/select', [BookController::class, 'selectBook'])->name('typing.select');

// Route for fetching a book from Project Gutenberg and redirecting to typing practice
Route::get('/books/fetch/{bookId}', [BookController::class, 'fetchFromGutenberg'])->name('books.fetch');

// Dashboard route (only accessible by authenticated users)
Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/api/books/demo', function (Request $request) {
    $book = Book::first(); // Change logic if you want a specific book marked as "demo"
    return response()->json($book);
});

// Profile routes for authenticated users
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

