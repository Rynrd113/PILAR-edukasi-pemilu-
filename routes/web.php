<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\MateriController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

// Public routes - User Frontend
Route::get('/', function () {
    return view('user.home');
})->name('home');

Route::get('/kategori', function () {
    return view('user.kategori');
})->name('kategori');

Route::get('/kategori/{slug}', function ($slug) {
    return view('user.kategori', ['slug' => $slug]);
})->name('kategori.show');

Route::get('/materi/{slug}', function ($slug) {
    return view('user.detail', ['slug' => $slug]);
})->name('materi.show');

// Auth routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Admin area
    Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
        // Dashboard
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Kategori management
        Route::resource('kategori', KategoriController::class);
        Route::patch('kategori/{kategori}/toggle-status', [KategoriController::class, 'toggleStatus'])->name('kategori.toggle-status');

        // Materi management
        Route::resource('materi', MateriController::class);
        Route::patch('materi/{materi}/toggle-publish', [MateriController::class, 'togglePublish'])->name('materi.toggle-publish');
        Route::patch('materi/{materi}/reset-views', [MateriController::class, 'resetViews'])->name('materi.reset-views');

        // User management
        Route::resource('users', UserController::class);
        Route::post('users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
    });
});

