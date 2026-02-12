<?php

use Illuminate\Support\Facades\Route;

use App\Services\ThemeService;

Route::get('/', function (ThemeService $themeService) {
    return view($themeService->view('home'));
})->name('home');
