<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

// Dashboard route
Route::get('/', [UserController::class, 'index'])->name('users.index');
Route::get('/dashboard', [UserController::class, 'index'])->name('dashboard');

// User routes
Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
Route::get('/users/datatable', [UserController::class, 'datatable'])->name('users.datatable');
Route::get('/users/all', [UserController::class, 'getAllUsers'])->name('users.all');