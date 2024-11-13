<?php

use Illuminate\Support\Facades\Route;
use App\Filament\Pages\Login;
// use App\Http\Controllers\AuthController;


Route::get('/', function () {
    return view('welcome');
});
Route::get('/welcome', function () {
    return view('welcome');
});

// Route::prefix('api')->group(function () {
//     Route::post('/login', [AuthController::class, 'login']);
//     Route::post('/register', [AuthController::class, 'register']);
//     Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
// });

