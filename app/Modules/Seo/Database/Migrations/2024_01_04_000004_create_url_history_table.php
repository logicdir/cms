<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('url_history', function (Blueprint $table) {
            $table->id();
            $table->string('content_type');
            $table->unsignedBigInteger('content_id');
            $table->string('old_slug');
            $table->string('new_slug');
            $table->timestamp('changed_at');

            $table->index(['content_type', 'content_id']);
            $table->index('old_slug');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('url_history');
    }
};
