<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::view('manage-plans', 'manage-plans')
    ->middleware(['auth', 'can:view all plans'])
    ->name('manage-plans');

require __DIR__.'/auth.php';
