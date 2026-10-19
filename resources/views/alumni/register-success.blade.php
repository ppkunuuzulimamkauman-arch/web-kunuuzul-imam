@extends('layouts.alumni')

@section('title', 'Pendaftaran Terkirim')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-16">
  <div class="bg-white border border-stone-200 rounded-3xl p-8 md:p-12 text-center shadow-sm">
    <div class="w-16 h-16 mx-auto rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center text-2xl">
      <i class="fa-solid fa-check"></i>
    </div>
    <h1 class="text-2xl font-extrabold text-stone-900 mt-5">Pendaftaran terkirim</h1>
    <p class="text-sm text-stone-500 leading-relaxed mt-3">
      Terima kasih. Data Anda sudah kami terima dan akan diverifikasi oleh admin.
      Setelah disetujui, profil Anda akan tampil di direktori alumni.
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
