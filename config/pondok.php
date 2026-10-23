<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Domain Utama
    |--------------------------------------------------------------------------
    |
    | Domain induk pondok tanpa skema (http/https). Dipakai untuk menyusun
    | domain tiap subdomain fungsional (contoh: alumni.{domain}).
    | Untuk lokal: set APP_DOMAIN=kunuuzul-imam-kauman.test di .env.
    |
    */

    'domain' => env('APP_DOMAIN', 'kunuuzulimamkauman.net'),

    /*
    |--------------------------------------------------------------------------
    | Subdomain Fungsional
    |--------------------------------------------------------------------------
    |
    | Setiap entri = satu "modul" di dalam satu aplikasi + satu database.
    | Kunci = label subdomain, nilai = nama file route di routes/subs/.
    | Tambahkan baris baru di sini lalu buat route + controller-nya.
    |
    | Prefix tabel per modul (disarankan): psb_, surat_, alumni_,
    | akademik_, keuangan_ — supaya tabel tidak tercampur meski satu DB.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Aplikasi Data Santri (terpisah)
    |--------------------------------------------------------------------------
    |
    | Website utama tidak lagi memuat modul PSB/SIAKAD/Alumni/Surat. Semua
    | tautan layanan diarahkan ke aplikasi Siberkah yang memiliki database dan
    | login admin sendiri.
    |
    */

    'siakad_url' => env('SIAKAD_URL', 'https://siberkah.kunuuzulimamkauman.net'),
    'psb_url' => env('PSB_URL', 'https://psb.kunuuzulimamkauman.net'),
    'surat_url' => env('SURAT_URL', 'https://surat.kunuuzulimamkauman.net'),
    'alumni_url' => env('ALUMNI_URL', 'https://alumni.kunuuzulimamkauman.net'),

];
