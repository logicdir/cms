<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contents', function (Blueprint $table) {
            $table->id();
            $table->string('type', 50)->index(); // post, page, custom
            $table->string('title');
            $table->string('slug');
            $table->text('excerpt')->nullable();
            $table->longText('content')->nullable();
            $table->enum('status', ['draft', 'published', 'scheduled', 'trashed'])->default('draft');
            $table->enum('visibility', ['public', 'private', 'password'])->default('public');
            $table->string('password')->nullable();
            $table->foreignId('author_id')->constrained('users')->onDelete('cascade');
            $table->unsignedBigInteger('featured_image_id')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->string('template')->nullable();
            $table->integer('order')->default(0);
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->unique(['slug', 'type']);
            $table->index(['status', 'published_at']);
            $table->fullText(['title', 'content'], 'contents_search');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contents');
    }
};
