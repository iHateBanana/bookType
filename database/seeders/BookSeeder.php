<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Services\ProjectGutenburgService;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        $gutenberg = new ProjectGutenburgService();

        // List of sample public domain Gutenberg IDs
        $ids = [
            84,     // Frankenstein
            1342,   // Pride and Prejudice
            11,     // Alice's Adventures in Wonderland
            1661,   // Sherlock Holmes
            2701,   // Moby Dick
            98,     // A Tale of Two Cities
            5200,   // Metamorphosis
            46,     // A Christmas Carol
            2542,   // Kama Sutra (yes, public domain)
            345,    // Dracula
        ];

        foreach ($ids as $id) {
            $book = $gutenberg->fetchBook($id);

            if ($book) {
                $this->command->info("Seeded: {$book->title}");
            } else {
                $this->command->warn("Failed to seed book ID: {$id}");
            }
        }
    }
}
