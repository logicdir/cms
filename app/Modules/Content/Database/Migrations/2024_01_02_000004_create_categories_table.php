<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->integer('lft')->default(0);
            $table->integer('rgt')->default(0);
            $table->integer('depth')->default(0);
            $table->unsignedBigInteger('image_id')->nullable();
            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('categories')->onDelete('cascade');
            $table->index('parent_id');
            $table->index(['lft', 'rgt']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
