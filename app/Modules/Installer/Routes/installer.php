<?php

use App\Modules\Installer\Http\Controllers\InstallerController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'installer'])->group(function () {
    Route::get('/install', [InstallerController::class, 'index'])->name('install.index');
    Route::get('/install/database', [InstallerController::class, 'database'])->name('install.database');
    Route::post('/install/test-connection', [InstallerController::class, 'testConnection'])->name('install.test');
    Route::get('/install/migration', [InstallerController::class, 'migration'])->name('install.migration');
    Route::post('/install/run-migration', [InstallerController::class, 'runMigration'])->name('install.migrate');
    Route::get('/install/account', [InstallerController::class, 'account'])->name('install.account');
    Route::post('/install/save-account', [InstallerController::class, 'saveAccount'])->name('install.save-account');
    Route::get('/install/complete', [InstallerController::class, 'complete'])->name('install.complete');
});
