<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('revisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('content_id')->constrained('contents')->onDelete('cascade');
            $table->string('title');
            $table->longText('content')->nullable();
            $table->text('excerpt')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamp('created_at');

            $table->index(['content_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('revisions');
    }
};
