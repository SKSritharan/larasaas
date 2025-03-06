<?php

use App\Http\Controllers\CheckoutController;
use App\Http\Middleware\Subscribed;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::view('/', 'welcome')
    ->name('home');

Route::middleware('guest')->group(function () {
    Volt::route('register/{planSlug}', 'pages.auth.register')
        ->name('register');

    Volt::route('login', 'pages.auth.login')
        ->name('login');

    Volt::route('forgot-password', 'pages.auth.forgot-password')
        ->name('password.request');

    Volt::route('reset-password/{token}', 'pages.auth.reset-password')
        ->name('password.reset');
});

Route::middleware('auth')->group(function () {
    Volt::route('verify-email', 'pages.auth.verify-email')
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Volt::route('confirm-password', 'pages.auth.confirm-password')
        ->name('password.confirm');
});

Route::middleware([Subscribed::class])->group(function () {
    Route::view('dashboard', 'dashboard')
        ->middleware(['auth', 'verified'])
        ->name('dashboard');

    Route::view('profile', 'profile')
        ->middleware(['auth'])
        ->name('profile');

    Route::view('billing', 'billing')
        ->middleware(['auth'])
        ->name('billing');

    Route::view('manage-plans', 'manage-plans')
        ->middleware(['auth', 'can:view all plans'])
        ->name('manage-plans');
});

Route::group(['prefix' => 'checkout'], function () {
    Route::get('plan/{planSlug}', [CheckoutController::class, 'checkout'])
        ->name('checkout');

    Route::get('plan/{planSlug}/success', [CheckoutController::class, 'success'])
        ->name('checkout-success');
})->middleware(['auth']);
