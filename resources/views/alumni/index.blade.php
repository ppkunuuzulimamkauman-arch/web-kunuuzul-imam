@extends('layouts.alumni')

@section('title', 'Direktori Alumni')

@section('content')
<section class="relative overflow-hidden bg-emerald-950 text-white">
  <div class="absolute -right-20 -top-24 h-80 w-80 rounded-full border-[32px] border-emerald-800/30"></div>
  <div class="absolute -left-24 bottom-[-9rem] h-80 w-80 rounded-full border-[42px] border-emerald-900/70"></div>
  <div class="relative max-w-7xl mx-auto px-4 py-14 md:py-20">
    <div class="max-w-3xl">
      <div class="inline-flex items-center gap-2 rounded-full border border-emerald-700/70 bg-emerald-900/60 px-3 py-1.5 text-[10px] font-extrabold tracking-[0.22em] text-emerald-200">
        <span class="h-1.5 w-1.5 rounded-full bg-amber-300"></span> IKATAN ALUMNI
      </div>
      <h1 class="text-4xl md:text-6xl font-extrabold tracking-tight mt-5 leading-[1.05]">Tetap terhubung,<br><span class="text-amber-300">tetap berkhidmat.</span></h1>
      <p class="text-emerald-100/80 mt-5 max-w-2xl text-sm md:text-base leading-relaxed">
        Direktori alumni Pondok Pesantren Kunuuzul Imam Kauman untuk menjaga silaturahmi lintas angkatan dan memperluas manfaat.
      </p>
    </div>
    <div class="mt-8 flex flex-wrap gap-3">
      <a href="{{ route('alumni.register') }}"
         class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-amber-300 text-emerald-950 text-sm font-extrabold shadow-lg shadow-emerald-950/20 hover:bg-amber-200 transition">
        <i class="fa-solid fa-user-plus"></i> Daftar sebagai Alumni
      </a>
      <a href="#statistik" class="inline-flex items-center gap-2 px-5 py-3 rounded-xl border border-emerald-700 text-emerald-100 text-sm font-extrabold hover:bg-emerald-900 transition">
        Lihat statistik <i class="fa-solid fa-arrow-down text-xs"></i>
      </a>
    </div>
    <div class="mt-10 flex flex-wrap gap-x-7 gap-y-3 text-xs text-emerald-200/80">
      <span><i class="fa-solid fa-shield-halved text-amber-300 mr-1.5"></i> Data ditampilkan setelah verifikasi</span>
      <span><i class="fa-solid fa-people-group text-amber-300 mr-1.5"></i> Terhubung lintas angkatan</span>
    </div>
  </div>
</section>

<div class="max-w-7xl mx-auto px-4 -mt-8 relative z-10">
  <div class="bg-white rounded-2xl border border-stone-200 shadow-xl shadow-stone-900/10 p-5 md:p-6">
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

<div id="statistik" class="max-w-7xl mx-auto px-4 py-14">
  <div class="mb-6">
    <p class="text-[11px] font-extrabold tracking-[0.3em] text-emerald-700">SEBARAN ALUMNI</p>
    <h2 class="text-2xl md:text-3xl font-extrabold text-stone-900 mt-2">Statistik Alumni</h2>
    <p class="text-sm text-stone-500 mt-1">Gambaran sebaran alumni berdasarkan angkatan, wilayah PK, dan domisili.</p>
  </div>

  <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
    <div class="bg-white border border-stone-200 rounded-2xl p-4 sm:p-5 shadow-sm">
      <p class="text-[10px] font-extrabold tracking-[0.2em] text-stone-400">TOTAL ALUMNI</p>
      <p class="text-3xl font-extrabold text-stone-900 mt-1">{{ $total }}</p>
      <p class="text-[12px] text-stone-400 mt-1">alumni terdata</p>
    </div>
    <div class="bg-white border border-stone-200 rounded-2xl p-4 sm:p-5 shadow-sm">
      <p class="text-[10px] font-extrabold tracking-[0.2em] text-stone-400">ANGKATAN</p>
      <p class="text-3xl font-extrabold text-stone-900 mt-1">{{ $jumlahAngkatan }}</p>
      <p class="text-[12px] text-stone-400 mt-1">angkatan terdata</p>
    </div>
    <div class="bg-white border border-stone-200 rounded-2xl p-4 sm:p-5 shadow-sm">
      <p class="text-[10px] font-extrabold tracking-[0.2em] text-stone-400">WILAYAH PK</p>
      <p class="text-3xl font-extrabold text-stone-900 mt-1">{{ $jumlahWilayahPk }}</p>
      <p class="text-[12px] text-stone-400 mt-1">wilayah PK terdata</p>
    </div>
    <div class="bg-white border border-stone-200 rounded-2xl p-4 sm:p-5 shadow-sm">
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

<section class="bg-white border-y border-stone-200">
  <div class="max-w-7xl mx-auto px-4 py-14 md:py-16">
    <div class="grid lg:grid-cols-[1fr_1.25fr] gap-10 lg:gap-16 items-center">
      <div>
        <p class="text-[11px] font-extrabold tracking-[0.3em] text-emerald-700">CARA BERGABUNG</p>
        <h2 class="text-2xl md:text-3xl font-extrabold text-stone-900 mt-2">Satu data untuk menjaga silaturahmi.</h2>
        <p class="text-sm text-stone-500 leading-relaxed mt-4 max-w-lg">
          Pastikan data Anda tercatat agar mudah ditemukan oleh sesama alumni dan pengurus. Semua pendaftaran akan diperiksa sebelum tampil di direktori.
        </p>
        <a href="{{ route('alumni.register') }}" class="inline-flex items-center gap-2 mt-6 px-5 py-3 rounded-xl bg-emerald-700 text-white text-sm font-extrabold hover:bg-emerald-800 transition">
          Lengkapi data saya <i class="fa-solid fa-arrow-right text-xs"></i>
        </a>
      </div>

      <div class="grid sm:grid-cols-3 gap-3">
        <div class="rounded-2xl bg-stone-50 border border-stone-200 p-5">
          <span class="flex items-center justify-center w-10 h-10 rounded-xl bg-emerald-100 text-emerald-700 font-extrabold">01</span>
          <h3 class="font-extrabold text-stone-900 mt-4">Isi data</h3>
          <p class="text-xs text-stone-500 leading-relaxed mt-2">Masukkan NIK dan biodata alumni dengan benar.</p>
        </div>
        <div class="rounded-2xl bg-stone-50 border border-stone-200 p-5">
          <span class="flex items-center justify-center w-10 h-10 rounded-xl bg-amber-100 text-amber-700 font-extrabold">02</span>
          <h3 class="font-extrabold text-stone-900 mt-4">Verifikasi</h3>
          <p class="text-xs text-stone-500 leading-relaxed mt-2">Pengurus memeriksa dan menyetujui data Anda.</p>
        </div>
        <div class="rounded-2xl bg-stone-50 border border-stone-200 p-5">
          <span class="flex items-center justify-center w-10 h-10 rounded-xl bg-sky-100 text-sky-700 font-extrabold">03</span>
          <h3 class="font-extrabold text-stone-900 mt-4">Terhubung</h3>
          <p class="text-xs text-stone-500 leading-relaxed mt-2">Profil tampil dan silaturahmi makin mudah.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="max-w-7xl mx-auto px-4 py-14">
  <div class="relative overflow-hidden rounded-3xl bg-emerald-900 px-6 py-10 md:px-12 md:py-12 text-white">
    <div class="absolute right-0 top-0 h-full w-1/3 bg-gradient-to-l from-emerald-700/40 to-transparent"></div>
    <div class="relative flex flex-col md:flex-row md:items-center md:justify-between gap-6">
      <div class="max-w-xl">
        <p class="text-[11px] font-extrabold tracking-[0.3em] text-amber-300">UNTUK ALUMNI KUNUUZUL IMAM</p>
        <h2 class="text-2xl md:text-3xl font-extrabold mt-2">Belum menemukan nama Anda?</h2>
        <p class="text-sm text-emerald-100/75 mt-3 leading-relaxed">Daftarkan diri sekarang. Data Anda akan ditinjau oleh admin sebelum masuk ke direktori.</p>
      </div>
      <a href="{{ route('alumni.register') }}" class="shrink-0 inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-amber-300 text-emerald-950 text-sm font-extrabold hover:bg-amber-200 transition">
        Daftar sekarang <i class="fa-solid fa-arrow-right text-xs"></i>
      </a>
    </div>
  </div>
</section>
@endsection
