<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\System;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('system');
    }
    return redirect('/login');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/', function () {
        return redirect()->route('system');
    })->name('home');

    // Route::get('/system', System::class)->name('system');
    
});

Route::get('/system', System::class)->name('system');