<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'book_id',
        'wpm',
        'accuracy',
        'completed_text',
    ];
}
