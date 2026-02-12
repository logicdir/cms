<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Core\Http\Controllers\Admin\CacheController;

// Admin Cache Management Routes
Route::middleware(['auth', 'verified'])->prefix('admin/cache')->group(function () {
    Route::get('/', [CacheController::class, 'index'])->name('admin.cache.index');
    Route::post('/clear', [CacheController::class, 'clear'])->name('admin.cache.clear');
    Route::post('/warm', [CacheController::class, 'warm'])->name('admin.cache.warm');
});
