<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ImageableController;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\PropertiController;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\ObjekController;
use App\Http\Controllers\KendaraanController;
use App\Http\Controllers\ReservasiController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\KetersediaanController;
use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\PembatalanController;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/update-password', [AuthController::class, 'updatePassword']);
    Route::post('/update-phone', [AuthController::class, 'gantiNomor']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::post('/pembatalan', [PembatalanController::class, 'createPembatalan']);

Route::get('/riwayat/{id_user}', [RiwayatController::class, 'index']);

Route::post('/cek-properti/{idProperti}', [KetersediaanController::class, 'cekKetersediaan']);
Route::post('/cek-kendaraan/{idKendaraan}', [KetersediaanController::class, 'cekKetersediaanKendaraan']);

Route::post('/reservasi/{id}/bayar', [PembayaranController::class, 'createPayment']);
Route::post('/midtrans/callback', [PembayaranController::class, 'notificationHandler']);

Route::post('/reservasi', [ReservasiController::class, 'storeReservasi']);

Route::get('/kendaraan', [KendaraanController::class, 'index']);

Route::get('/objek', [ObjekController::class, 'getObjekList']);

Route::get('/promo', [PromoController::class, 'index']);

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
// Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);


