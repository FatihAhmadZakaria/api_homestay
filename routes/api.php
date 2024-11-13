<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ImageableController;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\PropertiController;

Route::get('/properti', [PropertiController::class, 'index']);

Route::post('/images/upload', [ImageableController::class, 'store']);

Route::get('images/{filename}', function ($filename) {
    // Pastikan file ada di dalam direktori 'public/images'
    $path = storage_path('app/public/' . $filename);

    if (file_exists($path)) {
        return response()->file($path);
    }

    return response()->json(['error' => 'File not found'], 404);
});


Route::post('/products', [ProductController::class, 'store']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/welcome', function () {
    return response()->json(['message' => 'API route is working!']);
});

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);


