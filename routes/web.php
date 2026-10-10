<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\AgendaController;
use App\Http\Controllers\Admin\BeritaController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GaleriController;
use App\Http\Controllers\Admin\HeroSlideController;
use App\Http\Controllers\Admin\LembagaController;
use App\Http\Controllers\Admin\OrganisasiController;
use App\Http\Controllers\Admin\PendaftaranAdminController;
use App\Http\Controllers\Admin\PengasuhController;
use App\Http\Controllers\Admin\PesanController;
use App\Http\Controllers\Admin\ProgramController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KontakController;
use App\Http\Controllers\KonsultasiController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\PublicBeritaController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Sitemap dinamis untuk Google (halaman statis + berita + program + organisasi)
Route::get('/sitemap.xml', function () {
    $urls = [];
    foreach (['home','profil','profil.sejarah','profil.struktur','profil.visi','lembaga','organisasi','program.index','berita.index','artikel.index','galeri.index','ppdb.form','kontak','konsultasi'] as $name) {
        try { $urls[] = ['loc' => route($name), 'lastmod' => now()]; } catch (\Throwable $e) {}
    }
    try {
        $beritas = \App\Models\Berita::where('is_published', true)->latest('published_at')->get();
        foreach ($beritas as $b) {
            $urls[] = ['loc' => route('berita.show', $b->slug), 'lastmod' => $b->updated_at ?? $b->published_at ?? now()];
        }
        $programs = \App\Models\Program::where('is_aktif', true)->orderBy('urutan')->get();
        foreach ($programs as $p) {
            $urls[] = ['loc' => route('program.show', $p->slug), 'lastmod' => $p->updated_at ?? now()];
        }
        $orgs = \App\Models\Organisasi::where('is_aktif', true)->orderBy('urutan')->get();
        foreach ($orgs as $o) {
            $urls[] = ['loc' => route('organisasi.show', $o->slug), 'lastmod' => $o->updated_at ?? now()];
        }
    } catch (\Throwable $e) {
        // DB bermasalah: tetap tampilkan URL statis
    }
    return response()->view('sitemap', compact('urls'))->header('Content-Type', 'text/xml');
});
Route::get('/profil', [HomeController::class, 'profil'])->name('profil');
Route::get('/profil/sejarah', [HomeController::class, 'sejarah'])->name('profil.sejarah');
Route::get('/profil/struktur', [HomeController::class, 'struktur'])->name('profil.struktur');
Route::get('/profil/visi', [HomeController::class, 'visi'])->name('profil.visi');
Route::get('/lembaga', [HomeController::class, 'lembaga'])->name('lembaga');
Route::get('/organisasi', [HomeController::class, 'organisasi'])->name('organisasi');
Route::get('/organisasi/{slug}', [HomeController::class, 'organisasiShow'])->name('organisasi.show');
Route::get('/santri', [HomeController::class, 'santri'])->name('santri.index');
Route::get('/program', [HomeController::class, 'program'])->name('program.index');
Route::get('/program/{slug}', [HomeController::class, 'programShow'])->name('program.show');

Route::get('/berita', [PublicBeritaController::class, 'index'])->name('berita.index');
Route::get('/artikel', [PublicBeritaController::class, 'artikel'])->name('artikel.index');
Route::get('/berita/{slug}', [PublicBeritaController::class, 'show'])->name('berita.show');

Route::get('/galeri', [PendaftaranController::class, 'galeri'])->name('galeri.index');

Route::get('/ppdb', [PendaftaranController::class, 'create'])->name('ppdb.form');
Route::post('/ppdb', [PendaftaranController::class, 'store'])->middleware('throttle:10,1')->name('ppdb.store');
Route::get('/ppdb/sukses/{nomor}', [PendaftaranController::class, 'sukses'])->name('ppdb.sukses');

Route::get('/kontak', [KontakController::class, 'index'])->name('kontak');
Route::post('/kontak', [KontakController::class, 'store'])->middleware('throttle:10,1')->name('kontak.store');

Route::get('/konsultasi', [KonsultasiController::class, 'index'])->name('konsultasi');
Route::post('/konsultasi', [KonsultasiController::class, 'store'])->middleware('throttle:10,1')->name('konsultasi.store');

// === Auth Admin ===
Route::get('/admin/login', [AuthController::class, 'login'])->name('login')->middleware('guest');
Route::post('/admin/login', [AuthController::class, 'authenticate'])->middleware(['guest', 'throttle:5,1'])->name('login.post');
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout')->middleware('auth');

// === Panel Admin (wajib login + role admin/operator/superadmin) ===
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Berita & Artikel
    Route::resource('berita', BeritaController::class)->except(['show'])->parameters(['berita' => 'berita']);
    Route::post('berita/{berita}/toggle', [BeritaController::class, 'toggle'])->name('berita.toggle');

    // Program
    Route::resource('program', ProgramController::class)->except(['show']);

    // Galeri
    Route::resource('galeri', GaleriController::class)->except(['show']);

    // Hero slides
    Route::resource('hero', HeroSlideController::class)->except(['show'])->parameters(['hero' => 'hero']);

    // Lembaga
    Route::resource('lembaga', LembagaController::class)->except(['show']);

    // Organisasi (dalam & luar pesantren)
    Route::resource('organisasi', OrganisasiController::class)->except(['show']);

    // Pengasuh / Struktur
    Route::resource('pengasuh', PengasuhController::class)->except(['show']);

    // Agenda (Majelis Terdekat & Agenda Pondok di landing)
    Route::resource('agenda', AgendaController::class)->except(['show']);

    // PPDB / Pendaftaran santri
    Route::get('pendaftaran', [PendaftaranAdminController::class, 'index'])->name('pendaftaran.index');
    Route::get('pendaftaran/{pendaftaran}', [PendaftaranAdminController::class, 'show'])->name('pendaftaran.show');
    Route::patch('pendaftaran/{pendaftaran}/status', [PendaftaranAdminController::class, 'updateStatus'])->name('pendaftaran.status');
    Route::delete('pendaftaran/{pendaftaran}', [PendaftaranAdminController::class, 'destroy'])->name('pendaftaran.destroy');

    // Inbox pesan + pengajuan akun
    Route::get('pesan', [PesanController::class, 'index'])->name('pesan.index');
    Route::get('pesan/{pesan}', [PesanController::class, 'show'])->name('pesan.show');
    Route::post('pesan/{pesan}/dibaca', [PesanController::class, 'tandaiDibaca'])->name('pesan.dibaca');
    Route::delete('pesan/{pesan}', [PesanController::class, 'destroy'])->name('pesan.destroy');

    // Pengaturan website
    Route::get('setting', [SettingController::class, 'index'])->name('setting.index');
    Route::post('setting', [SettingController::class, 'update'])->name('setting.update');

    // Peta kontrol landing
    Route::view('panduan-landing', 'admin.panduan')->name('panduan');

    // Akun admin (1 role: admin)
    Route::resource('users', UserController::class)->except(['show']);
    Route::post('users/{user}/toggle', [UserController::class, 'toggleActive'])->name('users.toggle');
});
