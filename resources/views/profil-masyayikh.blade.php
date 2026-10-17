@extends('layouts.app')
@section('title', ($pengasuh->nama ?? 'Masyayikh') . ' — Masyayikh Pondok')
@section('content')
<div class="bg-white">
<div class="max-w-5xl mx-auto px-4 py-8 md:py-10">
  <p class="text-[12px] font-bold text-stone-400 tracking-wider"><a href="{{ route('home') }}" class="hover:text-orange-600 transition">Beranda</a> <span class="mx-1">/</span> <a href="{{ route('profil') }}" class="hover:text-orange-600 transition">Profil</a> <span class="mx-1">/</span> <a href="{{ route('profil.struktur') }}" class="hover:text-orange-600 transition">Masyayikh</a> <span class="mx-1">/</span> <span class="text-stone-600">{{ $pengasuh->nama }}</span></p>

  @php
    $foto = $pengasuh->foto ? (str_starts_with($pengasuh->foto, '/') || str_starts_with($pengasuh->foto, 'http') ? $pengasuh->foto : '/storage/' . $pengasuh->foto) : null;
  @endphp

  <div class="mt-5 flex flex-col sm:flex-row gap-6 items-center sm:items-start reveal">
    @if($foto)
    <img src="{{ $foto }}" alt="{{ $pengasuh->nama }}" class="w-32 h-32 rounded-full object-cover shrink-0 border-4 border-stone-100" onerror="this.style.display='none'">
    @else
    <div class="w-32 h-32 rounded-full bg-emerald-950 text-amber-400 flex items-center justify-center font-extrabold text-4xl shrink-0">{{ mb_substr($pengasuh->nama, 0, 1) }}</div>
    @endif
    <div class="text-center sm:text-left">
      <h1 class="font-extrabold text-stone-900 text-[26px] md:text-[30px] leading-tight">{{ $pengasuh->nama }}</h1>
      @if($pengasuh->jabatan)<p class="text-amber-600 font-bold text-[14px] mt-1">{{ $pengasuh->jabatan }}</p>@endif
      @if($pengasuh->bio)<p class="text-stone-500 text-[13.5px] mt-0.5">{{ $pengasuh->bio }}</p>@endif
      <div class="w-16 h-1 bg-orange-400 rounded-full mt-3 {{ $foto ? '' : 'mx-auto sm:mx-0' }}"></div>
    </div>
  </div>

  <div class="reveal border border-stone-200 rounded-2xl p-6 md:p-8 bg-white mt-7">
    <h2 class="font-extrabold text-stone-900 text-[18px]">Biografi</h2>
    <div class="text-[15px] leading-relaxed text-stone-600 mt-3 text-justify">
      @if($pengasuh->biografi)
        {!! nl2br(e($pengasuh->biografi)) !!}
      @else
        <p class="text-stone-400 italic">Biografi belum tersedia. Silakan lengkapi melalui panel admin.</p>
      @endif
    </div>
  </div>

  @if(isset($lainnya) && $lainnya->count())
  <h2 class="font-extrabold text-stone-900 text-[18px] mt-8">Masyayikh Lainnya</h2>
  <div class="w-12 h-1 bg-orange-400 rounded-full mt-2 mb-5"></div>
  <div class="grid sm:grid-cols-2 gap-5">
    @foreach($lainnya as $u)
    @php $f = $u->foto ? (str_starts_with($u->foto, '/') || str_starts_with($u->foto, 'http') ? $u->foto : '/storage/' . $u->foto) : null; @endphp
    <a href="{{ route('profil.masyayikh', $u->id) }}" class="reveal border border-stone-200 rounded-2xl p-5 flex gap-4 items-center hover:shadow-lg hover:-translate-y-1 transition bg-white group">
      @if($f)
      <img src="{{ $f }}" alt="{{ $u->nama }}" class="w-12 h-12 rounded-full object-cover shrink-0" onerror="this.style.display='none'">
      @else
      <div class="w-12 h-12 rounded-full bg-emerald-950 text-amber-400 flex items-center justify-center font-extrabold shrink-0">{{ mb_substr($u->nama, 0, 1) }}</div>
      @endif
      <div><h3 class="font-extrabold text-stone-900 text-[15px] group-hover:text-orange-600 transition">{{ $u->nama }}</h3><p class="text-stone-500 text-[13px]">{{ $u->jabatan }}</p></div>
    </a>
    @endforeach
  </div>
  @endif
</div>
</div>
@endsection
