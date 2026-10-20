@extends('layouts.surat')

@section('title', 'Layanan Surat Online')

@section('content')
@php
    $waDigits = preg_replace('/\D+/', '', (string) $waKontak);
    if (str_starts_with($waDigits, '0')) {
        $waDigits = '62'.substr($waDigits, 1);
    }
    $waUrl = strlen($waDigits) >= 10 && ! str_contains((string) $waKontak, 'x') ? 'https://wa.me/'.$waDigits : null;
@endphp

<section class="bg-emerald-900 text-white">
  <div class="max-w-7xl mx-auto px-4 py-16 md:py-24">
    <p class="text-[11px] font-extrabold tracking-[0.3em] text-emerald-300">LAYANAN ADMINISTRASI</p>
    <h1 class="text-3xl md:text-5xl font-extrabold mt-3">Layanan Surat Online</h1>
    <p class="text-emerald-100/80 mt-4 max-w-2xl text-sm md:text-base leading-relaxed">
      Ajukan surat keterangan, izin, rekomendasi, atau pengantar tanpa perlu datang ke kantor.
      Isi formulir, simpan kode permohonan, lalu pantau statusnya kapan saja.
    </p>
    <div class="mt-6 flex flex-wrap gap-3">
      <a href="{{ route('surat.create') }}"
         class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-white text-emerald-900 text-sm font-extrabold hover:bg-emerald-50 transition">
        <i class="fa-solid fa-pen-to-square"></i> Ajukan Surat
      </a>
      <a href="{{ route('surat.cek') }}"
         class="inline-flex items-center gap-2 px-5 py-3 rounded-xl border border-white/30 text-white text-sm font-extrabold hover:bg-white/10 transition">
        <i class="fa-solid fa-magnifying-glass"></i> Cek Status
      </a>
    </div>
  </div>
</section>

<div class="max-w-7xl mx-auto px-4 -mt-8 relative z-10">
  <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
    <div class="bg-white rounded-2xl border border-stone-200 shadow-lg p-5">
      <p class="text-[10px] font-extrabold tracking-[0.2em] text-stone-400">SURAT SELESAI</p>
      <p class="text-3xl font-extrabold text-stone-900 mt-1">{{ $jumlahSelesai }}</p>
      <p class="text-[12px] text-stone-400 mt-1">permohonan telah diselesaikan</p>
    </div>
    <div class="bg-white rounded-2xl border border-stone-200 shadow-lg p-5">
      <p class="text-[10px] font-extrabold tracking-[0.2em] text-stone-400">JAM LAYANAN</p>
      <p class="text-lg font-extrabold text-stone-900 mt-1">{{ $jamLayanan ?: 'Senin–Sabtu 08.00–15.00' }}</p>
      <p class="text-[12px] text-stone-400 mt-1">hari kerja kantor pondok</p>
    </div>
    <div class="bg-white rounded-2xl border border-stone-200 shadow-lg p-5">
      <p class="text-[10px] font-extrabold tracking-[0.2em] text-stone-400">TANPA ANTRE</p>
      <p class="text-lg font-extrabold text-stone-900 mt-1">100% Online</p>
      <p class="text-[12px] text-stone-400 mt-1">diajukan dari mana saja</p>
    </div>
  </div>
</div>

<div class="max-w-7xl mx-auto px-4 py-12">
  <div class="mb-6">
    <p class="text-[11px] font-extrabold tracking-[0.3em] text-emerald-700">JENIS LAYANAN</p>
    <h2 class="text-2xl md:text-3xl font-extrabold text-stone-900 mt-2">Surat yang Bisa Diajukan</h2>
    <p class="text-sm text-stone-500 mt-1">Pilih jenis surat yang sesuai dengan kebutuhan Anda.</p>
  </div>

  <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
    @foreach($jenisOptions as $key => $label)
      @php($meta = $jenisMeta[$key] ?? ['icon' => 'fa-solid fa-file-lines', 'desc' => '', 'syarat' => []])
      <div class="bg-white border border-stone-200 rounded-2xl p-5 flex flex-col">
        <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center text-lg">
          <i class="{{ $meta['icon'] }}"></i>
        </div>
        <h3 class="font-extrabold text-stone-900 mt-4 leading-tight">{{ $label }}</h3>
        <p class="text-[13px] text-stone-500 mt-2 leading-relaxed">{{ $meta['desc'] }}</p>
        @if(! empty($meta['syarat']))
          <ul class="mt-4 space-y-1.5 text-[12px] text-stone-500">
            @foreach($meta['syarat'] as $syarat)
              <li class="flex items-start gap-2">
                <i class="fa-solid fa-check text-emerald-600 text-[10px] mt-1"></i>
                <span>{{ $syarat }}</span>
              </li>
            @endforeach
          </ul>
        @endif
        <a href="{{ route('surat.create', ['jenis' => $key]) }}"
           class="mt-5 inline-flex items-center gap-2 text-[13px] font-extrabold text-emerald-700 hover:text-emerald-900 transition">
          Ajukan surat ini <i class="fa-solid fa-arrow-right text-[11px]"></i>
        </a>
      </div>
    @endforeach
  </div>
</div>

<div class="bg-white border-y border-stone-200">
  <div class="max-w-7xl mx-auto px-4 py-12">
    <div class="mb-8">
      <p class="text-[11px] font-extrabold tracking-[0.3em] text-emerald-700">ALUR PENGAJUAN</p>
      <h2 class="text-2xl md:text-3xl font-extrabold text-stone-900 mt-2">Empat Langkah Sederhana</h2>
    </div>

    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
      @foreach([
        ['fa-solid fa-file-pen', 'Isi Formulir', 'Lengkapi data diri, jenis surat, dan keperluan Anda.'],
        ['fa-solid fa-paper-plane', 'Kirim Permohonan', 'Dapatkan kode permohonan setelah formulir terkirim.'],
        ['fa-solid fa-user-clock', 'Verifikasi Admin', 'Petugas memeriksa kelengkapan dan memproses surat.'],
        ['fa-solid fa-circle-check', 'Ambil / Unduh', 'Pantau status dan unduh surat yang sudah selesai.'],
      ] as $i => $step)
        <div class="relative bg-stone-50 border border-stone-200 rounded-2xl p-5">
          <span class="absolute top-4 right-4 text-3xl font-extrabold text-stone-200">{{ $i + 1 }}</span>
          <div class="w-11 h-11 rounded-xl bg-emerald-700 text-white flex items-center justify-center text-lg">
            <i class="{{ $step[0] }}"></i>
          </div>
          <h3 class="font-extrabold text-stone-900 mt-4">{{ $step[1] }}</h3>
          <p class="text-[13px] text-stone-500 mt-1.5 leading-relaxed">{{ $step[2] }}</p>
        </div>
      @endforeach
    </div>
  </div>
</div>

<div class="max-w-7xl mx-auto px-4 py-12">
  <div class="grid lg:grid-cols-2 gap-6">
    <div class="bg-white border border-stone-200 rounded-2xl p-6">
      <h2 class="font-extrabold text-stone-900 text-lg">Catatan Sebelum Mengajukan</h2>
      <ul class="mt-4 space-y-3 text-[13px] text-stone-600">
        @foreach([
          'Data yang diisi harus benar dan sesuai identitas resmi.',
          'Kode permohonan wajib disimpan untuk mengecek status.',
          'Surat yang membutuhkan tanda tangan pengasuh diproses lebih lama.',
          'Permohonan yang tidak lengkap dapat ditolak dengan catatan dari admin.',
          'Untuk keperluan mendesak, silakan hubungi kantor pondok via WhatsApp.',
        ] as $catatan)
          <li class="flex items-start gap-2.5">
            <i class="fa-solid fa-circle-info text-emerald-600 text-[11px] mt-1"></i>
            <span>{{ $catatan }}</span>
          </li>
        @endforeach
      </ul>
    </div>

    <div class="bg-emerald-900 text-white rounded-2xl p-6 flex flex-col">
      <h2 class="font-extrabold text-lg">Butuh Bantuan?</h2>
      <p class="text-emerald-100/80 text-[13px] mt-2 leading-relaxed">
        Hubungi kantor pondok pada jam layanan bila ada kendala pengajuan atau ingin menanyakan
        proses surat Anda.
      </p>
      <dl class="mt-5 space-y-3 text-[13px]">
        <div class="flex items-start gap-2.5">
          <i class="fa-solid fa-clock text-emerald-300 mt-1"></i>
          <div>
            <dt class="font-extrabold">Jam Layanan</dt>
            <dd class="text-emerald-100/80">{{ $jamLayanan ?: 'Senin–Sabtu 08.00–15.00' }}</dd>
          </div>
        </div>
        @if($waKontak)
          <div class="flex items-start gap-2.5">
            <i class="fa-brands fa-whatsapp text-emerald-300 mt-1"></i>
            <div>
              <dt class="font-extrabold">WhatsApp</dt>
              <dd class="text-emerald-100/80">
                @if($waUrl)
                  <a href="{{ $waUrl }}" target="_blank" rel="noopener" class="hover:text-white underline underline-offset-2">{{ $waKontak }}</a>
                @else
                  {{ $waKontak }}
                @endif
              </dd>
            </div>
          </div>
        @endif
      </dl>
      <div class="mt-auto pt-6 flex flex-wrap gap-3">
        <a href="{{ route('surat.create') }}"
           class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-white text-emerald-900 text-sm font-extrabold hover:bg-emerald-50 transition">
          <i class="fa-solid fa-pen-to-square"></i> Mulai Ajukan
        </a>
        <a href="{{ route('surat.cek') }}"
           class="inline-flex items-center gap-2 px-5 py-3 rounded-xl border border-white/30 text-white text-sm font-extrabold hover:bg-white/10 transition">
          <i class="fa-solid fa-magnifying-glass"></i> Cek Status
        </a>
      </div>
    </div>
  </div>
</div>
@endsection
