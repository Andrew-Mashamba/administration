<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\System;
use App\Livewire\ProvisioningStatusList;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    return redirect('/login');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/system', System::class)->name('system');
    Route::get('/provisioning-status', ProvisioningStatusList::class)->name('provisioning.status');
});

