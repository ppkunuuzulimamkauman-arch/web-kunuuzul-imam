@extends('layouts.alumni')

@section('title', $alumni->nama)
@section('meta_description', 'Profil alumni '.$alumni->nama.' - PP Kunuuzul Imam Kauman.')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
  <a href="{{ route('alumni.home') }}" class="inline-flex items-center gap-2 text-[13px] font-bold text-stone-500 hover:text-emerald-700 transition">
    <i class="fa-solid fa-arrow-left"></i> Kembali ke direktori
  </a>

  <article class="bg-white border border-stone-200 rounded-3xl p-6 md:p-10 mt-4 shadow-sm">
    <div class="flex flex-col sm:flex-row gap-6 items-start">
      @if($alumni->fotoUrl())
        <img src="{{ $alumni->fotoUrl() }}" alt="Foto {{ $alumni->nama }}"
             class="w-28 h-28 md:w-36 md:h-36 rounded-3xl object-cover shrink-0 bg-stone-100">
      @else
        <div class="w-28 h-28 md:w-36 md:h-36 rounded-3xl bg-emerald-50 text-emerald-700 flex items-center justify-center text-4xl font-extrabold shrink-0">
          {{ strtoupper(substr($alumni->nama, 0, 1)) }}
        </div>
      @endif

      <div class="min-w-0">
        <p class="text-[11px] font-extrabold tracking-[0.25em] text-emerald-700">ALUMNI</p>
        <h1 class="text-2xl md:text-3xl font-extrabold text-stone-900 mt-1">{{ $alumni->nama }}</h1>
        @if($alumni->angkatanLabel())
          <p class="text-sm font-bold text-stone-500 mt-2">{{ $alumni->angkatanLabel() }}</p>
        @endif

        <div class="flex flex-wrap gap-2 mt-4">
          @if($alumni->lembaga)
            <span class="inline-flex items-center gap-2 text-[12px] font-bold px-3 py-1.5 rounded-full bg-stone-100 text-stone-600">
              <i class="fa-solid fa-mosque"></i> {{ $alumni->lembaga }}
            </span>
          @endif
          @if($alumni->domisili)
            <span class="inline-flex items-center gap-2 text-[12px] font-bold px-3 py-1.5 rounded-full bg-stone-100 text-stone-600">
              <i class="fa-solid fa-location-dot"></i> {{ $alumni->domisili }}
            </span>
          @endif
          @if($alumni->pekerjaan)
            <span class="inline-flex items-center gap-2 text-[12px] font-bold px-3 py-1.5 rounded-full bg-stone-100 text-stone-600">
              <i class="fa-solid fa-briefcase"></i> {{ $alumni->pekerjaan }}
            </span>
          @endif
        </div>
      </div>
    </div>

    <dl class="grid sm:grid-cols-2 gap-x-8 gap-y-5 mt-8 pt-8 border-t border-stone-100 text-sm">
      <div>
        <dt class="text-[11px] font-extrabold uppercase tracking-wider text-stone-400">Angkatan</dt>
        <dd class="font-bold text-stone-800 mt-1">{{ $alumni->angkatan ?: '-' }}</dd>
      </div>
      <div>
        <dt class="text-[11px] font-extrabold uppercase tracking-wider text-stone-400">Tahun Lulus</dt>
        <dd class="font-bold text-stone-800 mt-1">{{ $alumni->tahun_lulus ?: '-' }}</dd>
      </div>
      <div>
        <dt class="text-[11px] font-extrabold uppercase tracking-wider text-stone-400">Lembaga</dt>
        <dd class="font-bold text-stone-800 mt-1">{{ $alumni->lembaga ?: '-' }}</dd>
      </div>
      <div>
        <dt class="text-[11px] font-extrabold uppercase tracking-wider text-stone-400">Domisili</dt>
        <dd class="font-bold text-stone-800 mt-1">{{ $alumni->domisili ?: '-' }}</dd>
      </div>
      <div>
        <dt class="text-[11px] font-extrabold uppercase tracking-wider text-stone-400">Pekerjaan</dt>
        <dd class="font-bold text-stone-800 mt-1">{{ $alumni->pekerjaan ?: '-' }}</dd>
      </div>
      <div>
        <dt class="text-[11px] font-extrabold uppercase tracking-wider text-stone-400">Kontak</dt>
        <dd class="font-bold text-stone-800 mt-1 space-y-1">
          @if($alumni->waUrl())
            <a href="{{ $alumni->waUrl() }}" target="_blank" rel="noopener" class="block text-emerald-700 hover:underline">
              <i class="fa-brands fa-whatsapp mr-1"></i>{{ $alumni->no_hp }}
            </a>
          @endif
          @if($alumni->email)
            <a href="mailto:{{ $alumni->email }}" class="block text-emerald-700 hover:underline break-all">
              <i class="fa-solid fa-envelope mr-1"></i>{{ $alumni->email }}
            </a>
          @endif
          @if(! $alumni->waUrl() && ! $alumni->email)
            -
          @endif
        </dd>
      </div>
    </dl>

    @if($alumni->tentang)
      <div class="mt-8 pt-8 border-t border-stone-100">
        <h2 class="text-[11px] font-extrabold uppercase tracking-wider text-stone-400">Tentang</h2>
        <p class="text-sm text-stone-600 leading-relaxed mt-2 whitespace-pre-line">{{ $alumni->tentang }}</p>
      </div>
    @endif
  </article>
</div>
@endsection
