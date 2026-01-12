<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/update-emas', [App\Http\Controllers\AkunController::class, 'updateEmas']);
Route::get('/bangun-barak', [App\Http\Controllers\AkunController::class, 'bangunBarak']);
Route::post('/latih-pasukan', [App\Http\Controllers\AkunController::class, 'latihPasukan']);
Route::post('/serang-musuh', [App\Http\Controllers\AkunController::class, 'serangMusuh']);