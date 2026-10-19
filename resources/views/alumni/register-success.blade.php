@extends('layouts.alumni')

@section('title', 'Pendaftaran Terkirim')
@section('meta_description', 'Pendaftaran alumni PP Kunuuzul Imam Kauman berhasil dikirim.')
@section('robots', 'noindex, nofollow')

@section('content')
<section class="bg-emerald-900 text-white">
  <div class="max-w-7xl mx-auto px-4 py-10 md:py-14 text-center">
    <p class="text-[11px] font-extrabold tracking-[0.3em] text-emerald-300">PENDAFTARAN ALUMNI</p>
    <h1 class="text-3xl md:text-4xl font-extrabold mt-2">Pendaftaran terkirim</h1>
    <p class="text-emerald-100/80 text-sm mt-3">Terima kasih sudah melengkapi data alumni.</p>
  </div>
</section>

<div class="max-w-2xl mx-auto px-4 -mt-6 relative z-10">
  <div class="bg-white border border-stone-200 rounded-3xl p-8 md:p-12 text-center shadow-lg shadow-stone-200/60">
    <div class="w-16 h-16 mx-auto rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-8 h-8" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
      </svg>
    </div>

    <h2 class="text-xl md:text-2xl font-extrabold text-stone-900 mt-5">Data berhasil dikirim</h2>
    <p class="text-sm text-stone-500 leading-relaxed mt-3 max-w-lg mx-auto">
      Data Anda sudah kami terima dan akan diverifikasi oleh admin.
      Setelah disetujui, profil Anda akan tampil di direktori alumni.
    </p>
    <p class="text-[12px] text-stone-400 mt-3">
      Simpan NIK Anda untuk melihat data alumni di kemudian hari.
    </p>

    <div class="flex flex-wrap justify-center gap-3 mt-7">
      <a href="{{ route('alumni.home') }}"
         class="px-5 py-3 rounded-xl bg-emerald-700 text-white text-sm font-extrabold hover:bg-emerald-800 transition">
        Kembali ke Direktori
      </a>
      <a href="{{ route('alumni.register') }}"
         class="px-5 py-3 rounded-xl border border-stone-200 text-stone-600 text-sm font-extrabold hover:bg-stone-50 transition">
        Daftar Lagi
      </a>
    </div>
  </div>
</div>
@endsection
