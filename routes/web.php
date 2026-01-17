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
Route::post('/serang-musuh/{id}', [App\Http\Controllers\AkunController::class, 'serangMusuh'])->name('serang.musuh');
Route::get('/profile', [App\Http\Controllers\AkunController::class, 'profile'])->name('profile');
Route::get('/training-camp', [App\Http\Controllers\AkunController::class, 'trainingCamp'])->name('training.camp');
Route::post('/upgrade-level', [App\Http\Controllers\AkunController::class, 'prosesUpgrade'])->name('upgrade.process');
Route::get('/blacksmith', [App\Http\Controllers\AkunController::class, 'blacksmith'])->name('blacksmith');
Route::post('/upgrade-item', [App\Http\Controllers\AkunController::class, 'upgradeItem'])->name('upgrade.item');
Route::get('/laporan-perang', [App\Http\Controllers\AkunController::class, 'laporan'])->name('laporan');
