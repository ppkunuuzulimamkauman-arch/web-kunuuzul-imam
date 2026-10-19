@extends('layouts.alumni-dark')

@section('title', 'Pendaftaran Terkirim')
@section('meta_description', 'Pendaftaran alumni PP Kunuuzul Imam Kauman berhasil dikirim.')
@section('robots', 'noindex, nofollow')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-16 md:py-24">
  <div class="bg-slate-900/80 border border-white/10 rounded-2xl p-8 md:p-12 text-center shadow-2xl shadow-black/30">
    <div class="w-16 h-16 mx-auto rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center text-2xl">
      <i class="fa-solid fa-check"></i>
    </div>
    <h1 class="text-2xl md:text-3xl font-extrabold text-white mt-5">Pendaftaran terkirim</h1>
    <p class="text-sm text-slate-400 leading-relaxed mt-3 max-w-lg mx-auto">
      Terima kasih. Data Anda sudah kami terima dan akan diverifikasi oleh admin.
      Setelah disetujui, profil Anda akan tampil di direktori alumni.
    </p>
    <p class="text-[12px] text-slate-500 mt-3">
      Simpan NIK Anda untuk melihat data alumni di kemudian hari.
    </p>

    <div class="flex flex-wrap justify-center gap-3 mt-7">
      <a href="{{ route('alumni.home') }}"
         class="px-5 py-3 rounded-xl bg-emerald-600 text-white text-sm font-extrabold hover:bg-emerald-500 transition">
        Kembali ke Direktori
      </a>
      <a href="{{ route('alumni.register') }}"
         class="px-5 py-3 rounded-xl border border-white/10 text-slate-300 text-sm font-extrabold hover:bg-white/5 transition">
        Daftar Lagi
      </a>
    </div>
  </div>
</div>
@endsection
