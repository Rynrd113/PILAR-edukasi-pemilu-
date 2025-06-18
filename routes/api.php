<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\KategoriController;
use App\Http\Controllers\Api\MateriController;
use App\Http\Controllers\Api\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Public content routes
Route::get('/kategori', [KategoriController::class, 'index']);
Route::get('/kategori/{kategori}', [KategoriController::class, 'show'])->name('api.kategori.show');
Route::get('/materi', [MateriController::class, 'index']);
Route::get('/materi/{materi}', [MateriController::class, 'show'])->name('api.materi.show');
Route::get('/kategori/{kategori}/materi', [MateriController::class, 'byKategori']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth routes
    Route::post('/logout', [AuthController::class, 'logout']);

    // Admin routes
    Route::middleware('admin')->group(function () {
        // Kategori management
        Route::post('/kategori', [KategoriController::class, 'store']);
        Route::put('/kategori/{kategori}', [KategoriController::class, 'update']);
        Route::delete('/kategori/{kategori}', [KategoriController::class, 'destroy']);

        // Materi management
        Route::post('/materi', [MateriController::class, 'store']);
        Route::put('/materi/{materi}', [MateriController::class, 'update']);
        Route::delete('/materi/{materi}', [MateriController::class, 'destroy']);
    });
});

// Fallback route for when no other route matches
Route::fallback(function () {
    return response()->json([
        'status' => 'error',
        'message' => 'API endpoint not found'
    ], 404);
});
