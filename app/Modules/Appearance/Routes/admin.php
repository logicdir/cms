<?php

use App\Modules\Appearance\Http\Controllers\ThemeController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->middleware(['web', 'auth'])->group(function () {
    // Appearance → Themes
    Route::prefix('appearance')->name('appearance.')->group(function () {
        Route::get('themes', [ThemeController::class, 'index'])->name('themes.index');
        Route::post('themes/{id}/activate', [ThemeController::class, 'activate'])->name('themes.activate');
    });
});
