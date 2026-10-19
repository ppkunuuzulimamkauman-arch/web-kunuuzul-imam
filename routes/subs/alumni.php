<?php

use App\Http\Controllers\Alumni\AlumniController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AlumniController::class, 'index'])->name('home');
Route::get('/{alumni}', [AlumniController::class, 'show'])->whereNumber('alumni')->name('show');
