@extends('layouts.alumni-dark')

@section('title', 'Alumni Sudah Tercatat')
@section('meta_description', 'Data alumni PP Kunuuzul Imam Kauman.')
@section('robots', 'noindex, nofollow')

@section('content')
@php
    $statusClass = match ($alumni->status) {
        \App\Models\Alumni::STATUS_APPROVED => 'bg-emerald-500/10 text-emerald-300 border-emerald-500/30',
        \App\Models\Alumni::STATUS_PENDING => 'bg-amber-500/10 text-amber-300 border-amber-500/30',
        \App\Models\Alumni::STATUS_REJECTED => 'bg-red-500/10 text-red-300 border-red-500/30',
        default => 'bg-slate-500/10 text-slate-300 border-slate-500/30',
    };
@endphp

<div class="max-w-xl mx-auto px-4 py-12 md:py-20">
  <div class="bg-slate-900/80 border border-white/10 rounded-2xl p-6 md:p-8 text-center shadow-2xl shadow-black/30">
    <div class="w-16 h-16 mx-auto rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center text-2xl">
      <i class="fa-solid fa-id-card"></i>
    </div>

    <h1 class="text-2xl md:text-3xl font-extrabold text-white mt-5">Alumni sudah tercatat</h1>
    <p class="text-slate-400 text-sm mt-2">NIK Anda sudah terdaftar dalam data alumni.</p>

    <div class="mt-6 text-left rounded-xl border border-white/10 bg-slate-950/50 p-4">
      <div class="flex items-center justify-between gap-3 py-2 border-b border-white/5">
        <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-500">Nama</span>
        <span class="text-sm font-extrabold text-white text-right">{{ $alumni->nama }}</span>
      </div>
      <div class="flex items-center justify-between gap-3 py-2 border-b border-white/5">
        <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-500">ID Alumni</span>
        <span class="font-mono text-sm text-slate-200">{{ $alumni->kodeAlumni() }}</span>
      </div>
      <div class="flex items-center justify-between gap-3 py-2">
        <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-500">Status</span>
        <span class="text-[11px] font-extrabold px-2.5 py-1 rounded-full border {{ $statusClass }}">{{ $alumni->statusLabel() }}</span>
      </div>
    </div>

    <p class="text-[12px] text-slate-500 leading-relaxed mt-4">
      Data lain tidak ditampilkan demi privasi. Untuk mengubah data, silakan hubungi pengurus pesantren.
    </p>

    @if($alumni->status === \App\Models\Alumni::STATUS_PENDING)
      <p class="text-[13px] text-amber-300/90 mt-4">Pendaftaran Anda masih menunggu verifikasi pengurus.</p>
    @elseif($alumni->status === \App\Models\Alumni::STATUS_REJECTED)
      <p class="text-[13px] text-red-300/90 mt-4">Pendaftaran Anda belum disetujui. Silakan hubungi pengurus pesantren.</p>
    @elseif($alumni->is_tampil)
      <p class="text-[13px] text-emerald-300/90 mt-4">Profil Anda sudah tampil di direktori alumni.</p>
    @else
      <p class="text-[13px] text-slate-400 mt-4">Profil Anda sudah disetujui, tetapi belum ditampilkan di direktori.</p>
    @endif

    <div class="flex flex-col sm:flex-row justify-center gap-3 mt-7">
      @if($alumni->isPubliclyVisible())
        <a href="{{ route('alumni.show', $alumni) }}"
           class="px-5 py-3 rounded-xl bg-emerald-600 text-white text-sm font-extrabold hover:bg-emerald-500 transition">
          Lihat Profil Publik
        </a>
      @endif
      <a href="{{ route('alumni.register.reset') }}"
         class="px-5 py-3 rounded-xl border border-white/10 text-slate-300 text-sm font-extrabold hover:bg-white/5 transition">
        Daftarkan Alumni Lain
      </a>
    </div>

    <a href="{{ route('alumni.home') }}" class="inline-block text-[12px] font-bold text-slate-500 hover:text-slate-300 transition mt-5">
      Kembali ke Direktori
    </a>
  </div>
</div>
@endsection
