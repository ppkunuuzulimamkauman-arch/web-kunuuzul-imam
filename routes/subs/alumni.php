<?php

use App\Http\Controllers\Alumni\AlumniController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AlumniController::class, 'index'])->name('home');

Route::get('/daftar', [AlumniController::class, 'registerForm'])->name('register');
Route::post('/daftar', [AlumniController::class, 'registerStore'])
    ->middleware('throttle:5,1')
    ->name('register.store');
Route::get('/daftar/sukses', [AlumniController::class, 'registerSuccess'])->name('register.success');

Route::get('/{alumni}', [AlumniController::class, 'show'])->whereNumber('alumni')->name('show');
