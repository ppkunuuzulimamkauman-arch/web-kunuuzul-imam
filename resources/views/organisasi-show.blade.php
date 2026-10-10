@extends('layouts.app')
@section('title', $organisasi->singkatan.' — Organisasi')
@section('content')
<div class="bg-white">
<div class="max-w-3xl mx-auto px-4 py-8 md:py-10">
  <p class="text-[12px] font-bold text-stone-400 tracking-wider"><a href="{{ route('home') }}" class="hover:text-orange-600 transition">Beranda</a> <span class="mx-1">/</span> <a href="{{ route('organisasi') }}" class="hover:text-orange-600 transition">Organisasi</a> <span class="mx-1">/</span> <span class="text-stone-600">{{ $organisasi->singkatan }}</span></p>

  <div class="flex items-start gap-4 mt-4">
    @php
      $logo = $organisasi->logo ? (str_starts_with($organisasi->logo, '/') || str_starts_with($organisasi->logo, 'http') ? $organisasi->logo : '/storage/'.$organisasi->logo) : null;
    @endphp
    @if($logo)
    <img src="{{ $logo }}" alt="{{ $organisasi->singkatan }}" class="h-16 w-auto object-contain shrink-0">
    @else
    <div class="w-16 h-16 rounded-2xl bg-emerald-950 text-amber-400 flex items-center justify-center font-extrabold text-2xl shrink-0">{{ mb_substr($organisasi->singkatan, 0, 1) }}</div>
    @endif
    <div>
      <p><span class="text-[11px] font-extrabold tracking-widest px-3 py-1 rounded-full {{ $organisasi->kategori === 'dalam' ? 'bg-emerald-950 text-amber-300' : 'bg-amber-100 text-amber-700' }}">{{ $organisasi->kategori === 'dalam' ? 'DALAM PESANTREN' : 'LUAR — NAUNGAN PONDOK' }}</span></p>
      <h1 class="font-extrabold text-stone-900 text-[26px] md:text-[30px] leading-tight mt-2">{{ $organisasi->singkatan }}</h1>
      @if($organisasi->nama)<p class="text-[15px] font-bold text-amber-600 mt-1">{{ $organisasi->nama }}</p>@endif
    </div>
  </div>
  <div class="w-16 h-1 bg-orange-400 rounded-full mt-4 mb-6"></div>

  @if($organisasi->deskripsi)
  <div class="reveal border border-stone-200 rounded-2xl p-6 text-[15px] leading-relaxed text-stone-700">{!! nl2br(e($organisasi->deskripsi)) !!}</div>
  @elseif($organisasi->deskripsi_singkat)
  <div class="reveal border border-stone-200 rounded-2xl p-6 text-[15px] leading-relaxed text-stone-700">{{ $organisasi->deskripsi_singkat }}</div>
  @endif

  @if($organisasi->struktur)
  <h2 class="font-extrabold text-stone-900 text-[18px] mt-8">Struktur Pengurus</h2>
  <div class="w-12 h-1 bg-orange-400 rounded-full mt-2 mb-4"></div>
  <div class="reveal border border-stone-200 rounded-2xl p-6 bg-stone-50">
    <ul class="space-y-2.5 text-[14px] text-stone-600">
      @foreach(preg_split('/\r\n|\r|\n/', $organisasi->struktur) as $s)
      @php $parts = array_map('trim', explode('|', $s, 2)); @endphp
      @if(trim($s) !== '')<li class="flex gap-3"><span class="text-amber-500 font-extrabold">✓</span><span><b class="text-stone-900">{{ $parts[0] }}</b>{{ isset($parts[1]) && $parts[1] !== '' ? ' — '.$parts[1] : '' }}</span></li>@endif
      @endforeach
    </ul>
  </div>
  @endif

  @if($lainnya->count())
  <h3 class="font-extrabold text-stone-900 text-[18px] mt-10">Organisasi Lainnya</h3>
  <div class="w-12 h-1 bg-orange-400 rounded-full mt-2 mb-4"></div>
  <div class="grid sm:grid-cols-2 gap-4">
    @foreach($lainnya as $l)
    <a href="{{ route('organisasi.show', $l->slug) }}" class="reveal border border-stone-200 rounded-2xl p-5 hover:shadow-lg hover:-translate-y-1 hover:text-orange-600 transition">
      <p class="text-[14px] font-extrabold text-stone-800">{{ $l->singkatan }}</p>
      @if($l->nama)<p class="text-[12px] text-stone-500 mt-1">{{ $l->nama }}</p>@endif
    </a>
    @endforeach
  </div>
  @endif
</div>
</div>
@endsection
