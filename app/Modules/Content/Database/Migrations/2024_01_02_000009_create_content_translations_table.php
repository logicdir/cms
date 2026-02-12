<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('content_translations', function (Blueprint $table) {
            $table->foreignId('content_id')->constrained('contents')->onDelete('cascade');
            $table->string('locale', 5);
            $table->string('title');
            $table->string('slug');
            $table->text('excerpt')->nullable();
            $table->longText('content')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->timestamps();

            $table->primary(['content_id', 'locale']);
            $table->index('locale');
            $table->unique(['slug', 'locale']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('content_translations');
    }
};
