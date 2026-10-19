@extends('layouts.alumni')

@section('title', 'Alumni Sudah Tercatat')
@section('meta_description', 'Data alumni PP Kunuuzul Imam Kauman.')
@section('robots', 'noindex, nofollow')

@section('content')
@php
    $statusClass = match ($alumni->status) {
        \App\Models\Alumni::STATUS_APPROVED => 'bg-emerald-50 text-emerald-700 border-emerald-200',
        \App\Models\Alumni::STATUS_PENDING => 'bg-amber-50 text-amber-700 border-amber-200',
        \App\Models\Alumni::STATUS_REJECTED => 'bg-red-50 text-red-700 border-red-200',
        default => 'bg-stone-100 text-stone-600 border-stone-200',
    };
@endphp

<section class="bg-emerald-900 text-white">
  <div class="max-w-7xl mx-auto px-4 py-10 md:py-14 text-center">
    <p class="text-[11px] font-extrabold tracking-[0.3em] text-emerald-300">DATA ALUMNI</p>
    <h1 class="text-3xl md:text-4xl font-extrabold mt-2">Alumni sudah tercatat</h1>
    <p class="text-emerald-100/80 text-sm mt-3">NIK Anda sudah terdaftar dalam data alumni.</p>
  </div>
</section>

<div class="max-w-xl mx-auto px-4 -mt-6 relative z-10">
  <div class="bg-white border border-stone-200 rounded-3xl p-6 md:p-8 text-center shadow-lg shadow-stone-200/60">
    <div class="w-16 h-16 mx-auto rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" class="w-7 h-7" aria-hidden="true">
        <rect x="3" y="5" width="18" height="14" rx="2"></rect>
        <circle cx="9" cy="11" r="2"></circle>
        <path d="M6 16c.6-1.3 1.7-2 3-2s2.4.7 3 2"></path>
        <path d="M14 9h4M14 12h4M14 15h3"></path>
      </svg>
    </div>

    <h2 class="text-xl md:text-2xl font-extrabold text-stone-900 mt-5">Data Anda ditemukan</h2>
    <p class="text-stone-500 text-sm mt-2">Berikut ringkasan data alumni yang tercatat.</p>

    <div class="mt-6 text-left rounded-xl border border-stone-200 bg-stone-50 p-4">
      <div class="flex items-center justify-between gap-3 py-2 border-b border-stone-200">
        <span class="text-[11px] font-extrabold uppercase tracking-wider text-stone-400">Nama</span>
        <span class="text-sm font-extrabold text-stone-900 text-right">{{ $alumni->nama }}</span>
      </div>
      <div class="flex items-center justify-between gap-3 py-2 border-b border-stone-200">
        <span class="text-[11px] font-extrabold uppercase tracking-wider text-stone-400">ID Alumni</span>
        <span class="font-mono text-sm text-stone-700">{{ $alumni->kodeAlumni() }}</span>
      </div>
      <div class="flex items-center justify-between gap-3 py-2">
        <span class="text-[11px] font-extrabold uppercase tracking-wider text-stone-400">Status</span>
        <span class="text-[11px] font-extrabold px-2.5 py-1 rounded-full border {{ $statusClass }}">{{ $alumni->statusLabel() }}</span>
      </div>
    </div>

    <p class="text-[12px] text-stone-400 leading-relaxed mt-4">
      Data lain tidak ditampilkan demi privasi. Untuk mengubah data, silakan hubungi pengurus pesantren.
    </p>

    @if($alumni->status === \App\Models\Alumni::STATUS_PENDING)
      <p class="text-[13px] text-amber-700 mt-4">Pendaftaran Anda masih menunggu verifikasi pengurus.</p>
    @elseif($alumni->status === \App\Models\Alumni::STATUS_REJECTED)
      <p class="text-[13px] text-red-700 mt-4">Pendaftaran Anda belum disetujui. Silakan hubungi pengurus pesantren.</p>
    @elseif($alumni->is_tampil)
      <p class="text-[13px] text-emerald-700 mt-4">Profil Anda sudah tampil di direktori alumni.</p>
    @else
      <p class="text-[13px] text-stone-500 mt-4">Profil Anda sudah disetujui, tetapi belum ditampilkan di direktori.</p>
    @endif

    <div class="flex flex-col sm:flex-row justify-center gap-3 mt-7">
      @if($alumni->isPubliclyVisible())
        <a href="{{ route('alumni.show', $alumni) }}"
           class="px-5 py-3 rounded-xl bg-emerald-700 text-white text-sm font-extrabold hover:bg-emerald-800 transition">
          Lihat Profil Publik
        </a>
      @endif
      <a href="{{ route('alumni.register.reset') }}"
         class="px-5 py-3 rounded-xl border border-stone-200 text-stone-600 text-sm font-extrabold hover:bg-stone-50 transition">
        Daftarkan Alumni Lain
      </a>
    </div>

    <a href="{{ route('alumni.home') }}" class="inline-block text-[12px] font-bold text-stone-400 hover:text-stone-700 transition mt-5">
      Kembali ke Direktori
    </a>
  </div>
</div>
@endsection
