<?php

use App\Http\Controllers\Admin\Siakad\DashboardController;
use App\Http\Controllers\Admin\Siakad\KelasController;
use App\Http\Controllers\Admin\Siakad\SantriController;
use App\Http\Controllers\Admin\Siakad\TahunAjaranController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('santri', SantriController::class)->except(['show']);

Route::resource('tahun-ajaran', TahunAjaranController::class)
    ->except(['show'])
    ->parameters(['tahun-ajaran' => 'tahunAjaran']);

Route::resource('kelas', KelasController::class)
    ->except(['show'])
    ->parameters(['kelas' => 'kelas']);
