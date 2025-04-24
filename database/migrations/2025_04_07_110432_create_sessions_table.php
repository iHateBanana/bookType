<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // If you always need a user, no need for nullable
            $table->foreignId('book_id')->constrained()->onDelete('cascade');
            $table->decimal('wpm', 5, 2); // Use decimal for precision (e.g., 123.45 WPM)
            $table->decimal('accuracy', 5, 2); // Accuracy as percentage (e.g., 98.76%)
            $table->longText('completed_text');
            $table->integer('offset')->default(0);
            $table->enum('status', ['in_progress', 'completed', 'abandoned'])->default('in_progress');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
    }
};
