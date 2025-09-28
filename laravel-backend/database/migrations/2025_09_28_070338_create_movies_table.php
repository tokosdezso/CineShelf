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
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->integer('tmdb_id')->unique();
            $table->decimal('popularity', 8, 4)->default(0.0000);
            $table->decimal('vote_average', 4, 2)->default(0.00);
            $table->dateTime('release_date')->default(now());
            $table->string('poster_path')->nullable();
            $table->string('overview', 2000)->nullable();
            $table->integer('runtime');
            $table->string('language')->default('en');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movies');
    }
};
