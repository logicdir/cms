<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ad_units', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('code')->nullable();
            $table->enum('type', ['adsense', 'custom'])->default('adsense');
            $table->string('position')->nullable(); // header, sidebar_top, content_middle, etc.
            $table->string('size')->nullable(); // 300x250, responsive, etc.
            $table->boolean('responsive')->default(true);
            $table->boolean('status')->default(true);
            $table->boolean('auto_insert')->default(false);
            $table->json('display_rules')->nullable(); // category filters, device filters
            $table->timestamps();
        });

        Schema::create('ad_placements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ad_unit_id')->constrained()->onDelete('cascade');
            $table->string('content_type')->nullable(); // Page, Post
            $table->unsignedBigInteger('content_id')->nullable();
            $table->string('specific_position')->nullable();
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->integer('priority')->default(0);
            $table->integer('impressions_limit')->nullable();
            $table->timestamps();
        });

        Schema::create('ad_impressions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ad_unit_id')->constrained()->onDelete('cascade');
            $table->string('page_url');
            $table->text('user_agent')->nullable();
            $table->string('ip_address')->nullable(); // Should be hashed for privacy
            $table->boolean('clicked')->default(false);
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ad_impressions');
        Schema::dropIfExists('ad_placements');
        Schema::dropIfExists('ad_units');
    }
};
