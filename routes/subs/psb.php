<?php

use App\Http\Controllers\Psb\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Pintasan: alur pendaftaran tetap dikelola di domain utama (modul PPDB).
Route::get('/daftar', fn () => redirect()->away(rtrim((string) config('app.url'), '/').'/ppdb'))->name('daftar');
Route::get('/cek-status', fn () => redirect()->away(rtrim((string) config('app.url'), '/').'/ppdb/cek'))->name('cek');
