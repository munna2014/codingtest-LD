<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShortenUrlController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/users/datatable', [UserController::class, 'datatable'])->name('users.datatable');
Route::post('/users/{id}', [UserController::class, 'update'])->name('users.update');
Route::get('/users', [UserController::class, 'showAllUsers'])->name('users.index');

Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::get('/{shortCode}', [ShortenUrlController::class, 'redirectToOriginal'])->name('shortened.redirect');

require __DIR__.'/auth.php';