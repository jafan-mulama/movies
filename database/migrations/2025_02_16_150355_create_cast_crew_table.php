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
        Schema::create('cast_crews', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('bio')->nullable();
            $table->string('photo')->nullable();
            $table->enum('type', ['actor', 'director', 'producer', 'writer', 'other']);
            $table->timestamps();
            $table->softDeletes();
        });

        // Create pivot table for movies and cast/crew
        Schema::create('movie_cast_crew', function (Blueprint $table) {
            $table->id();
            $table->foreignId('movie_id')->constrained()->onDelete('cascade');
            $table->foreignId('cast_crew_id')->constrained('cast_crews')->onDelete('cascade');
            $table->string('role')->nullable(); // Specific role in the movie
            $table->string('character_name')->nullable(); // For actors
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movie_cast_crew');
        Schema::dropIfExists('cast_crews');
    }
};
