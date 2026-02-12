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
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('folder_id')->nullable()->constrained('media_folders')->onDelete('set null');
            $table->string('name'); // Original name
            $table->string('file_name'); // Stored filename
            $table->string('mime_type');
            $table->unsignedBigInteger('size');
            $table->integer('width')->nullable();
            $table->integer('height')->nullable();
            $table->string('disk')->default('public');
            $table->string('path');
            $table->string('url')->nullable();
            $table->string('alt_text')->nullable();
            $table->string('title')->nullable();
            $table->text('caption')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            $table->index('mime_type');
            $table->index('created_at');
        });

        // Add morph mapping for content relationships if needed later
        Schema::create('mediables', function (Blueprint $table) {
            $table->foreignId('media_id')->constrained('media')->onDelete('cascade');
            $table->morphs('mediable');
            $table->string('collection_name')->nullable();
            $table->integer('order')->nullable();
            
            $table->primary(['media_id', 'mediable_id', 'mediable_type', 'collection_name'], 'mediables_primary');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mediables');
        Schema::dropIfExists('media');
    }
};
