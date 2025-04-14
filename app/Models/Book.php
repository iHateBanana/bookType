<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Book extends Model
{
    use HasFactory;

    protected $table = 'books';

    protected $fillable = [
        'title', 'author', 'cover_image', 'text', 'gutenberg_id'
    ];

    // Optionally, you can also define the relationship if needed (e.g., for user-generated content or progress)
    public function userProgress()
    {
        return $this->hasMany(UserProgress::class); // Assuming you have a 'UserProgress' model for tracking user's progress
    }

    /**
     * Accessor for the cover image URL.
     * This method ensures that the cover image is correctly accessible.
     *
     * @return string
     */
    public function getCoverUrlAttribute()
    {
        return $this->cover_image ? Storage::url($this->cover_image) : 'default-cover.jpg'; // Fallback to a default cover
    }

    /**
     * Accessor for the book text.
     * You may want to process or format the text in a certain way before displaying it.
     *
     * @return string
     */
    public function getFormattedTextAttribute()
    {
        return nl2br(e($this->text)); // Example of formatting text, turning newlines into <br> tags for HTML rendering
    }

    // You can add any additional helper methods for manipulating books (like fetching specific books)
    public static function getBookByGutenbergId($gutenbergId)
    {
        return self::where('gutenberg_id', $gutenbergId)->first(); // Example method to fetch a book by Gutenberg ID
    }
}
