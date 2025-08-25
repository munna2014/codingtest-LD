<?php
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\PasswordController;

// ----------------------
// Dashboard
// ----------------------
Route::get('/', [UserController::class, 'index'])->name('dashboard');
Route::get('/dashboard', [UserController::class, 'index'])->name('dashboard');

// ----------------------
// Profile
// ----------------------

Route::middleware('auth')->group(function () {
    Route::get('/profile', function () {
    return view('profile'); // resources/views/profile.blade.php
})->name('profile.view');

    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::put('/password', [PasswordController::class, 'update'])->name('password.update');
});

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

use App\Http\Controllers\Auth\LoginController;

// Login routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');