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

    'subdomains' => [
        'psb' => 'psb',
        'surat' => 'surat',
        'alumni' => 'alumni',
        'siakad' => 'siakad',
    ],

];
