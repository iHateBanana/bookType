<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypingSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id',
        'wpm',
        'accuracy',
        'completed_text',
    ];

    // Add any relationships if needed (e.g., with the Book model)
    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
