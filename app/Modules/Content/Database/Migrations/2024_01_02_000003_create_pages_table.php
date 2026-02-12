<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->foreignId('content_id')->primary()->constrained('contents')->onDelete('cascade');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->integer('lft')->default(0);
            $table->integer('rgt')->default(0);
            $table->integer('depth')->default(0);

            $table->foreign('parent_id')->references('content_id')->on('pages')->onDelete('cascade');
            $table->index('parent_id');
            $table->index(['lft', 'rgt']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
