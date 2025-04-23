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
            //5200,    Metamorphosis
            46,     // A Christmas Carol
            2542,   // Kama Sutra
            345,    // Dracula
            30254, // The Picture of Dorian Gray by Oscar Wilde
            3600,  // Tractatus Logico-Philosophicus by Ludwig Wittgenstein
            158,   // Emma by Jane Austen
            2591,  // Grimms' Fairy Tales by Jacob & Wilhelm Grimm
            8800,  // The Communist Manifesto by Marx & Engels
            408,   // Three Men in a Boat by Jerome K. Jerome
            204,   // Robinson Crusoe by Daniel Defoe
            4363,  // The Iliad by Homer (translated)
            6130,  // The Republic by Plato (translated)
            55,    // The Wonderful Wizard of Oz by L. Frank Baum
            19942, // The Art of War by Sun Tzu
            3090,  // Aesop’s Fables
            514,   // The King in Yellow by Robert W. Chambers
            14591, // Thus Spake Zarathustra by Friedrich Nietzsche
            105,   // Persuasion by Jane Austen
            10609, // Beowulf (translated)
            205,   // Uncle Tom’s Cabin by Harriet Beecher Stowe
            767,   // Beyond Good and Evil by Nietzsche

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
