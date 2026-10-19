@extends('layouts.alumni')

@section('title', 'Direktori Alumni')

@section('content')
<section class="bg-emerald-900 text-white">
  <div class="max-w-7xl mx-auto px-4 py-16 md:py-24">
    <p class="text-[11px] font-extrabold tracking-[0.3em] text-emerald-300">IKATAN ALUMNI</p>
    <h1 class="text-3xl md:text-5xl font-extrabold mt-3">Direktori Alumni</h1>
    <p class="text-emerald-100/80 mt-4 max-w-2xl text-sm md:text-base leading-relaxed">
      Silaturahmi lintas angkatan Pondok Pesantren Kunuuzul Imam Kauman. Masukkan NIK
      untuk menemukan data alumni yang sudah terdaftar.
    </p>
    <div class="mt-6 flex flex-wrap gap-3">
      <a href="{{ route('alumni.register') }}"
         class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-white text-emerald-900 text-sm font-extrabold hover:bg-emerald-50 transition">
        <i class="fa-solid fa-user-plus"></i> Daftar sebagai Alumni
      </a>
    </div>
  </div>
</section>

<div class="max-w-7xl mx-auto px-4 -mt-8 relative z-10">
  <div class="bg-white rounded-2xl border border-stone-200 shadow-lg p-4 md:p-5">
    @include('alumni.partials.nik-check', [
      'inline' => true,
      'source' => 'directory',
      'nikValue' => $nik,
    ])
  </div>
</div>

@if($searched)
  <div id="direktori" class="max-w-7xl mx-auto px-4 py-10">
    @if($hasil)
      <div class="flex items-end justify-between gap-4 mb-6">
        <div>
          <h2 class="font-extrabold text-stone-900 text-xl">Daftar Alumni</h2>
          <p class="text-sm text-stone-500 mt-1">Menampilkan 1 dari 1 alumni</p>
        </div>
      </div>

      <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
        @include('alumni.partials.card', ['alumni' => $hasil])
      </div>
    @else
      <div class="bg-white border border-stone-200 rounded-2xl p-10 text-center">
        <div class="w-14 h-14 mx-auto rounded-2xl bg-stone-100 text-stone-400 flex items-center justify-center text-xl">
          <i class="fa-solid fa-user-graduate"></i>
        </div>
        <h3 class="font-extrabold text-stone-900 mt-4">Data tidak ditemukan</h3>
        <p class="text-sm text-stone-500 mt-1">{{ $nikError }}</p>
      </div>
    @endif
  </div>
@endif
@endsection
