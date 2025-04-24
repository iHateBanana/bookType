<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBooksTable extends Migration
{
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->integer('gutenberg_id')->unique();
            $table->string('title');
            $table->string('author')->nullable();
            $table->string('language')->default('en');
            $table->text('download_url');
            $table->longText('text')->nullable();
            $table->text('cover_url')->nullable();
            $table->timestamps();

        });
    }

    public function down()
    {
        Schema::dropIfExists('books');
    }
}
