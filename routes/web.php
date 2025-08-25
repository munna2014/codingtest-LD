<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserController;

// ----------------------
// Dashboard
// ----------------------
Route::get('/', [UserController::class, 'index'])->name('dashboard');
Route::get('/dashboard', [UserController::class, 'index'])->name('dashboard');

// ----------------------
// Profile
// ----------------------
Route::get('/profile', function () {
    return view('profile'); // resources/views/profile.blade.php
})->name('profile.view');

Route::get('/profile/edit', function () {
    return view('profile-edit'); // resources/views/profile-edit.blade.php
})->name('profile.edit');

// ----------------------
// Users
// ----------------------
Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
Route::get('/users/datatable', [UserController::class, 'datatable'])->name('users.datatable');
Route::get('/users/all', [UserController::class, 'getAllUsers'])->name('users.all');

// ----------------------
// Logout
// ----------------------
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/'); // After logout, send to homepage
})->name('logout');