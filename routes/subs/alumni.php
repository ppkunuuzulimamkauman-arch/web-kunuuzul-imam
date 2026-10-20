<?php

use App\Http\Controllers\Alumni\AlumniController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AlumniController::class, 'index'])->name('home');

Route::get('/daftar', [AlumniController::class, 'registerFormFields'])->name('register');
Route::post('/daftar', [AlumniController::class, 'checkNik'])
    ->middleware('throttle:10,1')
    ->name('register.check');
Route::get('/daftar/form', [AlumniController::class, 'registerFormFields'])->name('register.form');
Route::post('/daftar/form', [AlumniController::class, 'registerStore'])
    ->middleware('throttle:5,1')
    ->name('register.store');
Route::get('/daftar/tercatat', [AlumniController::class, 'registerRecorded'])->name('register.recorded');
Route::get('/daftar/ganti-nik', [AlumniController::class, 'resetNik'])->name('register.reset');
Route::get('/daftar/sukses', [AlumniController::class, 'registerSuccess'])->name('register.success');

Route::get('/{alumni}', [AlumniController::class, 'show'])->whereNumber('alumni')->name('show');
