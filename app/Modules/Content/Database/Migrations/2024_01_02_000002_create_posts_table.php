<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->foreignId('content_id')->primary()->constrained('contents')->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null');
            $table->boolean('allow_comments')->default(true);
            $table->integer('comment_count')->default(0);

            $table->index('category_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
