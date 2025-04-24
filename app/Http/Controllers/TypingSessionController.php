<?php

namespace App\Http\Controllers;

use App\Models\TypingSession; // Make sure you import the TypingSession model
use Illuminate\Http\Request;

class TypingSessionController extends Controller
{
    public function store(Request $request)
    {
        // Validate the incoming data
        $validated = $request->validate([
            'book_id' => 'required|exists:books,id',
            'wpm' => 'required|numeric',
            'accuracy' => 'required|numeric',
            'completed_text' => 'required|string',
        ]);

        // Save the session to the database (you can modify the storage logic)
        TypingSession::create([
            'book_id' => $validated['book_id'],
            'wpm' => $validated['wpm'],
            'accuracy' => $validated['accuracy'],
            'completed_text' => $validated['completed_text'],
        ]);

        // Return a response to the client
        return response()->json(['message' => 'Session saved successfully.']);
    }
}
