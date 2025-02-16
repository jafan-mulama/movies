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
            $table->text('synopsis');
            $table->string('thumbnail')->nullable();
            $table->string('trailer_url')->nullable();
            $table->year('release_year');
            $table->string('director');
            $table->string('genre');
            $table->integer('duration')->comment('Duration in minutes');
            $table->enum('type', ['free', 'premium'])->default('premium');
            $table->boolean('featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->decimal('rating', 3, 1)->nullable();
            $table->integer('views')->default(0);
            $table->timestamps();
            $table->softDeletes();
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
