<?php

use App\Http\Controllers\Surat\SuratController;
use Illuminate\Support\Facades\Route;

Route::get('/', [SuratController::class, 'index'])->name('home');

Route::get('/ajukan', [SuratController::class, 'create'])->name('create');
Route::post('/ajukan', [SuratController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('store');
Route::get('/ajukan/sukses/{kode}', [SuratController::class, 'sukses'])->name('sukses');

Route::get('/cek', [SuratController::class, 'cek'])
    ->middleware('throttle:30,1')
    ->name('cek');
