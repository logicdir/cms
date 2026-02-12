<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/hello', function () {
    return Inertia::render('HelloModule::Index', [
        'message' => 'Hello from the LogicDir Module System!'
    ]);
})->name('hello.index');
