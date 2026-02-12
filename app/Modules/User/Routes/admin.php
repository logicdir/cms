<?php

use App\Modules\User\Http\Controllers\Admin\DashboardController;
use App\Modules\User\Http\Controllers\Admin\RoleController;
use App\Modules\User\Http\Controllers\Admin\UserController;
use App\Modules\User\Http\Controllers\Admin\SettingController;
use App\Modules\User\Http\Controllers\Admin\PlatformController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->middleware(['web', 'auth'])->group(function () {
    // Platform Administration
    Route::get('platform', [PlatformController::class, 'index'])->name('platform.index');

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('dashboard', [DashboardController::class, 'index']);

    // Settings
    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::get('settings/general', [SettingController::class, 'general'])->name('settings.general');
    Route::post('settings/general', [SettingController::class, 'updateGeneral'])->name('settings.general.update');

    // User Management
    Route::resource('users', UserController::class);
    Route::post('users/bulk-destroy', [UserController::class, 'bulkDestroy'])->name('users.bulk-destroy');
    Route::post('users/{id}/restore', [UserController::class, 'restore'])->name('users.restore');

    // Role & Permission Management
    Route::resource('roles', RoleController::class)->except(['show']);
    Route::post('roles/{role}/permissions', [RoleController::class, 'syncPermissions'])->name('roles.permissions');
});
