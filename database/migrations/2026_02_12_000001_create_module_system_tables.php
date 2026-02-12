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
        // Modules metadata table
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('version')->default('1.0.0');
            $table->text('description')->nullable();
            $table->string('author')->nullable();
            $table->string('path')->comment('Relative path to module directory');
            $table->boolean('is_active')->default(false);
            $table->json('providers')->nullable()->comment('Custom service providers to load');
            $table->integer('priority')->default(10);
            $table->timestamps();
            
            $table->index('slug');
            $table->index('is_active');
        });

        // Module dependencies tracking
        Schema::create('module_dependencies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained('modules')->onDelete('cascade');
            $table->string('dependent_slug');
            $table->string('version_constraint')->nullable();
            $table->timestamps();

            $table->index(['module_id', 'dependent_slug']);
        });

        // Module-specific settings
        Schema::create('module_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained('modules')->onDelete('cascade');
            $table->string('key');
            $table->json('value')->nullable();
            $table->timestamps();

            $table->unique(['module_id', 'key']);
        });

        // Lifecycle logs for audit trail
        Schema::create('module_logs', function (Blueprint $table) {
            $table->id();
            $table->string('module_slug');
            $table->enum('action', ['install', 'uninstall', 'activate', 'deactivate', 'update']);
            $table->string('version')->nullable();
            $table->string('user_id')->nullable()->comment('User who performed the action');
            $table->text('message')->nullable();
            $table->timestamps();

            $table->index('module_slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('module_logs');
        Schema::dropIfExists('module_settings');
        Schema::dropIfExists('module_dependencies');
        Schema::dropIfExists('modules');
    }
};
