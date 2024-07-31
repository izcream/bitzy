<?php

use App\Http\Controllers\Admin\ManageLinkController;
use App\Http\Controllers\Admin\ManageUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LinkController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RedirectController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/', [LinkController::class, 'index'])->name('index');
    Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
    Route::resource('links', LinkController::class)->only(['store', 'destroy']);
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile');
    Route::patch('/profile', [ProfileController::class, 'update']);
    Route::name('admin.')->prefix('admin')->middleware(['can:manage-link'])->group(function () {
        Route::resource('/links', ManageLinkController::class);
        Route::resource('/users', ManageUserController::class);
    });
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('auth.login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('auth.register');
    Route::post('/register', [AuthController::class, 'register'])->name('auth.register');

    Route::get('/forgot-password', [PasswordResetController::class, 'showForgotPassword'])->name('password.email');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink']);
    Route::get('/reset-password', [PasswordResetController::class, 'showResetPassword'])->name('password.request');
    Route::post('/reset-password', [PasswordResetController::class, 'resetPassword'])->name('password.reset');
});
Route::get('/go/{shortened_url}', [RedirectController::class, 'handleRedirect']);
