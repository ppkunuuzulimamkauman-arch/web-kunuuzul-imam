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

<div class="max-w-7xl mx-auto px-4 py-10">
  <div class="mb-6">
    <p class="text-[11px] font-extrabold tracking-[0.3em] text-emerald-700">STATISTIK</p>
    <h2 class="text-2xl md:text-3xl font-extrabold text-stone-900 mt-2">Statistik Alumni</h2>
    <p class="text-sm text-stone-500 mt-1">Gambaran sebaran alumni berdasarkan angkatan, wilayah PK, dan domisili.</p>
  </div>

  <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
    <div class="bg-white border border-stone-200 rounded-2xl p-5">
      <p class="text-[10px] font-extrabold tracking-[0.2em] text-stone-400">TOTAL ALUMNI</p>
      <p class="text-3xl font-extrabold text-stone-900 mt-1">{{ $total }}</p>
      <p class="text-[12px] text-stone-400 mt-1">alumni terdata</p>
    </div>
    <div class="bg-white border border-stone-200 rounded-2xl p-5">
      <p class="text-[10px] font-extrabold tracking-[0.2em] text-stone-400">ANGKATAN</p>
      <p class="text-3xl font-extrabold text-stone-900 mt-1">{{ $jumlahAngkatan }}</p>
      <p class="text-[12px] text-stone-400 mt-1">angkatan terdata</p>
    </div>
    <div class="bg-white border border-stone-200 rounded-2xl p-5">
      <p class="text-[10px] font-extrabold tracking-[0.2em] text-stone-400">WILAYAH PK</p>
      <p class="text-3xl font-extrabold text-stone-900 mt-1">{{ $jumlahWilayahPk }}</p>
      <p class="text-[12px] text-stone-400 mt-1">wilayah PK terdata</p>
    </div>
    <div class="bg-white border border-stone-200 rounded-2xl p-5">
      <p class="text-[10px] font-extrabold tracking-[0.2em] text-stone-400">DOMISILI</p>
      <p class="text-3xl font-extrabold text-stone-900 mt-1">{{ $jumlahDomisili }}</p>
      <p class="text-[12px] text-stone-400 mt-1">kota / kabupaten</p>
    </div>
  </div>

  @if($total === 0)
    <div class="bg-white border border-stone-200 rounded-2xl p-10 text-center mt-8">
      <div class="w-14 h-14 mx-auto rounded-2xl bg-stone-100 text-stone-400 flex items-center justify-center text-xl">
        <i class="fa-solid fa-chart-simple"></i>
      </div>
      <h2 class="font-extrabold text-stone-900 mt-4">Belum ada data statistik</h2>
      <p class="text-sm text-stone-500 mt-1">Statistik akan tampil setelah ada alumni yang disetujui.</p>
      <a href="{{ route('alumni.register') }}"
         class="inline-flex items-center gap-2 mt-5 px-5 py-3 rounded-xl bg-emerald-700 text-white text-sm font-extrabold hover:bg-emerald-800 transition">
        <i class="fa-solid fa-user-plus"></i> Daftar sebagai Alumni
      </a>
    </div>
  @else
    <div class="grid lg:grid-cols-2 gap-6 mt-8">
      <div class="bg-white border border-stone-200 rounded-2xl p-6">
        <h2 class="font-extrabold text-stone-900 text-lg">Alumni per Angkatan</h2>
        <p class="text-[12px] text-stone-400 mt-1">Jumlah alumni yang tampil di direktori.</p>

        <div class="mt-6 space-y-4">
          @foreach($perAngkatan as $row)
            <div>
              <div class="flex items-center justify-between gap-3 text-[12px] font-bold">
                <span class="text-stone-600">Angkatan {{ $row->angkatan }}</span>
                <span class="text-emerald-700">{{ $row->total }} alumni</span>
              </div>
              <div class="h-2.5 bg-stone-100 rounded-full mt-1.5 overflow-hidden">
                <div class="h-full bg-emerald-600 rounded-full"
                     style="width: {{ $maxAngkatan > 0 ? max(2, (int) round($row->total / $maxAngkatan * 100)) : 0 }}%"></div>
              </div>
            </div>
          @endforeach
        </div>
      </div>

      <div class="space-y-6">
        <div class="bg-white border border-stone-200 rounded-2xl p-6">
          <h2 class="font-extrabold text-stone-900 text-lg">Wilayah PK Terbanyak</h2>
          <p class="text-[12px] text-stone-400 mt-1">7 wilayah PK dengan alumni terbanyak.</p>

          <div class="mt-3">
            @forelse($perWilayahPk as $row)
              <div class="flex items-center justify-between gap-3 py-2.5 border-b border-stone-100 last:border-0">
                <span class="text-sm font-bold text-stone-700 truncate">Wilayah {{ $row->wilayah_pk }}</span>
                <span class="text-[11px] font-extrabold px-2.5 py-1 rounded-full bg-stone-100 text-stone-600 shrink-0">{{ $row->total }}</span>
              </div>
            @empty
              <p class="text-sm text-stone-400">Belum ada data wilayah PK.</p>
            @endforelse
          </div>
        </div>

        <div class="bg-white border border-stone-200 rounded-2xl p-6">
          <h2 class="font-extrabold text-stone-900 text-lg">Domisili Terbanyak</h2>
          <p class="text-[12px] text-stone-400 mt-1">8 kota / kabupaten dengan alumni terbanyak.</p>

          <div class="mt-3">
            @forelse($perDomisili as $row)
              <div class="flex items-center justify-between gap-3 py-2.5 border-b border-stone-100 last:border-0">
                <span class="text-sm font-bold text-stone-700 truncate">{{ $row->domisili }}</span>
                <span class="text-[11px] font-extrabold px-2.5 py-1 rounded-full bg-stone-100 text-stone-600 shrink-0">{{ $row->total }}</span>
              </div>
            @empty
              <p class="text-sm text-stone-400">Belum ada data domisili.</p>
            @endforelse
          </div>
        </div>
      </div>
    </div>

  @endif
</div>
@endsection
