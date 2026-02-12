<?php

use App\Modules\Media\Http\Controllers\Admin\MediaController;
use App\Modules\Media\Http\Controllers\Admin\MediaFolderController;
use Illuminate\Support\Facades\Route;

Route::get('/', [MediaController::class, 'index'])->name('index');
Route::post('/upload', [MediaController::class, 'store'])->name('upload');
Route::post('/upload-chunk', [MediaController::class, 'uploadChunk'])->name('upload.chunk');
Route::patch('/{media}', [MediaController::class, 'update'])->name('update');
Route::delete('/bulk-delete', [MediaController::class, 'destroy'])->name('bulk-delete');
Route::post('/move', [MediaController::class, 'move'])->name('move');

Route::prefix('folders')->name('folders.')->group(function () {
    Route::post('/', [MediaFolderController::class, 'store'])->name('store');
    Route::patch('/{folder}', [MediaFolderController::class, 'update'])->name('update');
    Route::delete('/{folder}', [MediaFolderController::class, 'destroy'])->name('destroy');
});
