<?php

use Illuminate\Support\Facades\Route;
use App\Filament\Pages\Login;


Route::get('/', function () {
    return view('welcome');
});
