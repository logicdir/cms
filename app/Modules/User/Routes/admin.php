<?php

use App\Modules\User\Http\Controllers\Admin\RoleController;
use App\Modules\User\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->middleware(['web', 'auth'])->group(function () {
    // User Management
    Route::resource('users', UserController::class);
    Route::post('users/bulk-destroy', [UserController::class, 'bulkDestroy'])->name('users.bulk-destroy');
    Route::post('users/{id}/restore', [UserController::class, 'restore'])->name('users.restore');

    // Role & Permission Management
    Route::resource('roles', RoleController::class)->except(['show']);
    Route::post('roles/{role}/permissions', [RoleController::class, 'syncPermissions'])->name('roles.permissions');
});
