@extends('layouts.alumni')

@section('title', 'Direktori Alumni')

@section('content')
<section class="bg-emerald-900 text-white">
  <div class="max-w-7xl mx-auto px-4 py-16 md:py-24">
    <p class="text-[11px] font-extrabold tracking-[0.3em] text-emerald-300">IKATAN ALUMNI</p>
    <h1 class="text-3xl md:text-5xl font-extrabold mt-3">Direktori Alumni</h1>
    <p class="text-emerald-100/80 mt-4 max-w-2xl text-sm md:text-base leading-relaxed">
      Silaturahmi lintas angkatan Pondok Pesantren Kunuuzul Imam Kauman. Temukan teman seangkatan,
      rekan selembaga, dan jaringan alumni di seluruh Indonesia.
    </p>
    <div class="mt-6 flex flex-wrap gap-3">
      <a href="{{ route('alumni.register') }}"
         class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-white text-emerald-900 text-sm font-extrabold hover:bg-emerald-50 transition">
        <i class="fa-solid fa-user-plus"></i> Daftar sebagai Alumni
      </a>
      <a href="#direktori"
         class="inline-flex items-center gap-2 px-5 py-3 rounded-xl border border-emerald-300/40 text-emerald-50 text-sm font-extrabold hover:bg-emerald-800/50 transition">
        <i class="fa-solid fa-users"></i> Lihat Direktori
      </a>
    </div>
  </div>
</section>

<div class="max-w-7xl mx-auto px-4 -mt-8 relative z-10">
  <form method="GET" action="{{ route('alumni.home') }}" class="bg-white rounded-2xl border border-stone-200 shadow-lg p-4 md:p-5 grid gap-3 md:grid-cols-[1fr_200px_auto]">
    <label class="relative block">
      <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-stone-400 text-sm"></i>
      <input name="q" value="{{ $q }}" placeholder="Cari nama, lembaga, domisili, pekerjaan..."
             class="w-full pl-11 pr-4 py-3 rounded-xl border border-stone-200 bg-stone-50 text-sm text-stone-800 focus:outline-none focus:border-emerald-600 focus:bg-white transition">
    </label>
    <select name="angkatan" class="w-full px-4 py-3 rounded-xl border border-stone-200 bg-stone-50 text-sm text-stone-800 focus:outline-none focus:border-emerald-600 focus:bg-white transition">
      <option value="">Semua angkatan</option>
      @foreach($angkatans as $tahun)
        <option value="{{ $tahun }}" @selected((string) $angkatan === (string) $tahun)>Angkatan {{ $tahun }}</option>
      @endforeach
    </select>
    <button type="submit" class="px-6 py-3 rounded-xl bg-emerald-700 text-white text-sm font-extrabold hover:bg-emerald-800 transition">
      Cari
    </button>
  </form>
</div>

<div id="direktori" class="max-w-7xl mx-auto px-4 py-10">
  <div class="flex items-end justify-between gap-4 mb-6">
    <div>
      <h2 class="font-extrabold text-stone-900 text-xl">Daftar Alumni</h2>
      <p class="text-sm text-stone-500 mt-1">
        Menampilkan {{ $alumnis->count() }} dari {{ $alumnis->total() }} alumni
      </p>
    </div>
    @if($q !== '' || $angkatan)
      <a href="{{ route('alumni.home') }}" class="text-[13px] font-bold text-emerald-700 hover:underline shrink-0">Reset filter</a>
    @endif
  </div>

  @if($alumnis->isEmpty())
    <div class="bg-white border border-stone-200 rounded-2xl p-10 text-center">
      <div class="w-14 h-14 mx-auto rounded-2xl bg-stone-100 text-stone-400 flex items-center justify-center text-xl">
        <i class="fa-solid fa-user-graduate"></i>
      </div>
      <h3 class="font-extrabold text-stone-900 mt-4">Belum ada data alumni</h3>
      <p class="text-sm text-stone-500 mt-1">
        @if($q !== '' || $angkatan)
          Coba ubah kata kunci atau reset filter.
        @else
          Data alumni akan tampil di sini setelah diisi oleh pengelola.
        @endif
      </p>
    </div>
  @else
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
      @foreach($alumnis as $alumni)
        <a href="{{ route('alumni.show', $alumni) }}"
           class="group bg-white border border-stone-200 rounded-2xl p-5 flex gap-4 hover:shadow-xl hover:-translate-y-0.5 transition">
          @if($alumni->fotoUrl())
            <img src="{{ $alumni->fotoUrl() }}" alt="Foto {{ $alumni->nama }}"
                 class="w-20 h-20 rounded-2xl object-cover shrink-0 bg-stone-100">
          @else
            <div class="w-20 h-20 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center text-2xl font-extrabold shrink-0">
              {{ strtoupper(substr($alumni->nama, 0, 1)) }}
            </div>
          @endif
          <div class="min-w-0">
            <h3 class="font-extrabold text-stone-900 leading-tight group-hover:text-emerald-700 transition">
              {{ $alumni->nama }}
            </h3>
            @if($alumni->angkatanLabel())
              <p class="text-[12px] font-bold text-emerald-700 mt-1">{{ $alumni->angkatanLabel() }}</p>
            @endif
            @if($alumni->lembaga)
              <p class="text-[13px] text-stone-500 mt-1 truncate">{{ $alumni->lembaga }}</p>
            @endif
            @if($alumni->domisili)
              <p class="text-[12px] text-stone-400 mt-1 truncate">
                <i class="fa-solid fa-location-dot mr-1"></i>{{ $alumni->domisili }}
              </p>
            @endif
          </div>
        </a>
      @endforeach
    </div>

    <div class="mt-8">
      {{ $alumnis->links('vendor.pagination.alumni') }}
    </div>
  @endif
</div>
@endsection
