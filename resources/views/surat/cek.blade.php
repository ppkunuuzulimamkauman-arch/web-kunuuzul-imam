@extends('layouts.surat')

@section('title', 'Cek Status Permohonan Surat')

@section('content')
@php
    $waDigits = preg_replace('/\D+/', '', (string) ($waKontak ?? ''));
    if (str_starts_with($waDigits, '0')) {
        $waDigits = '62'.substr($waDigits, 1);
    }
    $waUrl = strlen($waDigits) >= 10 && ! str_contains((string) $waKontak, 'x') ? 'https://wa.me/'.$waDigits : null;
    $step = $permohonan?->progressStep() ?? 0;
    $ditolak = $permohonan && $permohonan->status === \App\Models\SuratPermohonan::STATUS_DITOLAK;
    $selesai = $permohonan && $permohonan->status === \App\Models\SuratPermohonan::STATUS_SELESAI;
@endphp

<section class="bg-emerald-900 text-white">
  <div class="max-w-3xl mx-auto px-4 py-12 md:py-16 text-center">
    <p class="text-[11px] font-extrabold tracking-[0.3em] text-emerald-300">PANTAU PERMOHONAN</p>
    <h1 class="text-3xl md:text-4xl font-extrabold mt-2">Cek Status Surat</h1>
    <p class="text-emerald-100/80 text-sm mt-3 max-w-xl mx-auto">
      Masukkan kode permohonan yang Anda terima saat mengajukan surat, misalnya
      <span class="font-mono font-bold text-white">SRT-2610-AB12CD</span>.
    </p>
  </div>
</section>

<div class="max-w-3xl mx-auto px-4 -mt-6 relative z-10">
  <div class="bg-white rounded-2xl border border-stone-200 shadow-lg p-4 md:p-5">
    <form method="GET" action="{{ route('surat.cek') }}" class="flex flex-col sm:flex-row sm:items-start gap-3">
      <div class="flex-1 min-w-0">
        <label for="kode" class="text-[12px] font-extrabold text-stone-700">Kode permohonan</label>
        <input id="kode" name="kode" required value="{{ $kode }}" autocomplete="off"
               placeholder="SRT-XXXXXX-XXXXXX"
               class="mt-1.5 w-full px-4 py-3 rounded-xl border border-stone-200 bg-stone-50 text-sm text-stone-900 placeholder-stone-400 font-mono tracking-wider uppercase focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/30 transition">
        @if($kodeError)
          <p class="text-red-600 text-[12px] font-bold mt-2">{{ $kodeError }}</p>
        @endif
      </div>
      <button type="submit"
              class="sm:mt-[26px] w-full sm:w-auto px-8 py-3 rounded-xl bg-emerald-700 text-white text-sm font-extrabold hover:bg-emerald-800 transition">
        <i class="fa-solid fa-magnifying-glass mr-1"></i> Cek
      </button>
    </form>
  </div>
</div>

@if($searched && $permohonan)
  <div class="max-w-3xl mx-auto px-4 py-10">
    <div class="bg-white border border-stone-200 rounded-3xl shadow-sm overflow-hidden">
      <div class="p-6 md:p-8 border-b border-stone-100 flex flex-wrap items-start justify-between gap-4">
        <div class="flex items-start gap-4">
          <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center text-lg shrink-0">
            <i class="{{ $permohonan->jenisIcon() }}"></i>
          </div>
          <div>
            <p class="text-[11px] font-extrabold tracking-[0.18em] text-stone-400 font-mono">{{ $permohonan->kode }}</p>
            <h2 class="font-extrabold text-stone-900 text-lg mt-1">{{ $permohonan->jenisLabel() }}</h2>
            <p class="text-[13px] text-stone-500 mt-1">Pemohon: {{ $permohonan->nama }}</p>
          </div>
        </div>
        <span class="inline-flex items-center gap-1.5 text-[11px] font-extrabold px-3 py-1.5 rounded-full
          {{ $ditolak ? 'bg-red-100 text-red-700' : ($selesai ? 'bg-emerald-100 text-emerald-700' : ($permohonan->status === \App\Models\SuratPermohonan::STATUS_DIPROSES ? 'bg-blue-100 text-blue-700' : 'bg-amber-100 text-amber-800')) }}">
          <i class="fa-solid {{ $ditolak ? 'fa-circle-xmark' : ($selesai ? 'fa-circle-check' : 'fa-clock') }}"></i>
          {{ $permohonan->statusLabel() }}
        </span>
      </div>

      <div class="p-6 md:p-8 border-b border-stone-100">
        <p class="font-extrabold text-stone-900 text-sm mb-5">Progres Permohonan</p>
        <ol class="space-y-0">
          <li class="flex gap-3">
            <div class="flex flex-col items-center">
              <span class="w-8 h-8 rounded-full flex items-center justify-center text-[12px] font-extrabold {{ $step >= 1 ? 'bg-emerald-700 text-white' : 'bg-stone-100 text-stone-400' }}">
                <i class="fa-solid fa-check"></i>
              </span>
              <span class="w-0.5 h-8 {{ $step >= 2 ? 'bg-emerald-600' : 'bg-stone-200' }}"></span>
            </div>
            <div class="pb-5">
              <p class="font-extrabold text-[13px] text-stone-900">Permohonan diajukan</p>
              <p class="text-[12px] text-stone-500 mt-0.5">{{ $permohonan->created_at?->locale('id')->translatedFormat('d F Y, H:i') }}</p>
            </div>
          </li>

          <li class="flex gap-3">
            <div class="flex flex-col items-center">
              <span class="w-8 h-8 rounded-full flex items-center justify-center text-[12px] font-extrabold {{ $ditolak ? 'bg-red-600 text-white' : ($step >= 2 ? 'bg-emerald-700 text-white' : 'bg-stone-100 text-stone-400') }}">
                <i class="fa-solid {{ $ditolak ? 'fa-xmark' : 'fa-user-clock' }}"></i>
              </span>
              <span class="w-0.5 h-8 {{ $selesai ? 'bg-emerald-600' : 'bg-stone-200' }}"></span>
            </div>
            <div class="pb-5">
              <p class="font-extrabold text-[13px] text-stone-900">{{ $ditolak ? 'Permohonan ditolak' : 'Verifikasi & proses admin' }}</p>
              <p class="text-[12px] text-stone-500 mt-0.5">
                @if($ditolak)
                  Silakan periksa catatan admin dan hubungi kantor pondok bila perlu.
                @elseif($step >= 2)
                  Permohonan sedang dikerjakan oleh petugas.
                @else
                  Menunggu diperiksa petugas.
                @endif
              </p>
            </div>
          </li>

          <li class="flex gap-3">
            <div class="flex flex-col items-center">
              <span class="w-8 h-8 rounded-full flex items-center justify-center text-[12px] font-extrabold {{ $selesai ? 'bg-emerald-700 text-white' : 'bg-stone-100 text-stone-400' }}">
                <i class="fa-solid fa-flag-checkered"></i>
              </span>
            </div>
            <div>
              <p class="font-extrabold text-[13px] text-stone-900">Surat selesai</p>
              <p class="text-[12px] text-stone-500 mt-0.5">
                @if($selesai)
                  Selesai pada {{ $permohonan->selesai_at?->locale('id')->translatedFormat('d F Y, H:i') ?? $permohonan->updated_at?->locale('id')->translatedFormat('d F Y, H:i') }}.
                @else
                  Surat dapat diambil setelah status selesai.
                @endif
              </p>
            </div>
          </li>
        </ol>
      </div>

      <dl class="p-6 md:p-8 grid sm:grid-cols-2 gap-x-6 gap-y-4 text-sm">
        <div>
          <dt class="text-[11px] font-extrabold tracking-[0.15em] text-stone-400">JENIS SURAT</dt>
          <dd class="font-bold text-stone-800 mt-1">{{ $permohonan->jenisLabel() }}</dd>
        </div>
        <div>
          <dt class="text-[11px] font-extrabold tracking-[0.15em] text-stone-400">TANGGAL AJUAN</dt>
          <dd class="font-bold text-stone-800 mt-1">{{ $permohonan->created_at?->locale('id')->translatedFormat('d F Y') }}</dd>
        </div>
        @if($permohonan->nomor_surat)
          <div>
            <dt class="text-[11px] font-extrabold tracking-[0.15em] text-stone-400">NOMOR SURAT</dt>
            <dd class="font-bold text-stone-800 mt-1 font-mono">{{ $permohonan->nomor_surat }}</dd>
          </div>
        @endif
        @if($permohonan->jenis === 'izin_pulang' && $permohonan->tanggal_mulai)
          <div>
            <dt class="text-[11px] font-extrabold tracking-[0.15em] text-stone-400">PERIODE IZIN</dt>
            <dd class="font-bold text-stone-800 mt-1">
              {{ $permohonan->tanggal_mulai->translatedFormat('d M Y') }}
              @if($permohonan->tanggal_selesai)
                – {{ $permohonan->tanggal_selesai->translatedFormat('d M Y') }}
              @endif
            </dd>
          </div>
        @endif
      </dl>

      @if($permohonan->catatan_admin)
        <div class="px-6 md:px-8 pb-6 md:pb-8">
          <div class="rounded-2xl border {{ $ditolak ? 'border-red-200 bg-red-50' : 'border-emerald-200 bg-emerald-50' }} p-5">
            <p class="font-extrabold text-[13px] {{ $ditolak ? 'text-red-800' : 'text-emerald-900' }}">
              <i class="fa-solid fa-comment-dots mr-1"></i> Catatan dari admin
            </p>
            <p class="text-[13px] mt-1.5 whitespace-pre-line {{ $ditolak ? 'text-red-700' : 'text-emerald-800' }}">{{ $permohonan->catatan_admin }}</p>
          </div>
        </div>
      @endif

      @if($permohonan->fileHasilUrl())
        <div class="px-6 md:px-8 pb-6 md:pb-8">
          <a href="{{ $permohonan->fileHasilUrl() }}" target="_blank" rel="noopener"
             class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-emerald-700 text-white text-sm font-extrabold hover:bg-emerald-800 transition">
            <i class="fa-solid fa-download"></i> Unduh Surat
          </a>
          <p class="text-[12px] text-stone-400 mt-2">Berkas surat yang sudah diproses admin.</p>
        </div>
      @endif

      <div class="px-6 md:px-8 py-5 bg-stone-50 border-t border-stone-100 flex flex-wrap items-center justify-between gap-3">
        <p class="text-[12px] text-stone-500">
          <i class="fa-solid fa-circle-info text-emerald-600 mr-1"></i>
          Simpan halaman ini atau kode permohonan untuk pengecekan berikutnya.
        </p>
        <div class="flex flex-wrap gap-2">
          @if($waUrl)
            <a href="{{ $waUrl }}?text={{ rawurlencode("Assalamu'alaikum, saya ingin menanyakan permohonan surat dengan kode {$permohonan->kode} atas nama {$permohonan->nama}.") }}"
               target="_blank" rel="noopener"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-stone-200 bg-white text-stone-700 text-[12px] font-extrabold hover:bg-stone-100 transition">
              <i class="fa-brands fa-whatsapp text-emerald-600"></i> Hubungi Admin
            </a>
          @endif
          <a href="{{ route('surat.create') }}"
             class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-700 text-white text-[12px] font-extrabold hover:bg-emerald-800 transition">
            <i class="fa-solid fa-plus"></i> Ajukan Surat Baru
          </a>
        </div>
      </div>
    </div>
  </div>
@elseif($searched)
  <div class="max-w-3xl mx-auto px-4 py-10">
    <div class="bg-white border border-stone-200 rounded-2xl p-10 text-center">
      <div class="w-14 h-14 mx-auto rounded-2xl bg-stone-100 text-stone-400 flex items-center justify-center text-xl">
        <i class="fa-solid fa-file-circle-question"></i>
      </div>
      <h3 class="font-extrabold text-stone-900 mt-4">Permohonan tidak ditemukan</h3>
      <p class="text-sm text-stone-500 mt-1 max-w-md mx-auto">{{ $kodeError }}</p>
      <div class="mt-5 flex flex-wrap justify-center gap-3">
        <a href="{{ route('surat.create') }}"
           class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-emerald-700 text-white text-sm font-extrabold hover:bg-emerald-800 transition">
          <i class="fa-solid fa-pen-to-square"></i> Ajukan Surat
        </a>
        <a href="{{ route('surat.home') }}"
           class="inline-flex items-center gap-2 px-5 py-3 rounded-xl border border-stone-200 text-stone-600 text-sm font-extrabold hover:bg-stone-50 transition">
          Beranda
        </a>
      </div>
    </div>
  </div>
@else
  <div class="max-w-3xl mx-auto px-4 py-10">
    <div class="bg-white border border-stone-200 rounded-2xl p-6 md:p-8">
      <h2 class="font-extrabold text-stone-900">Belum punya kode permohonan?</h2>
      <p class="text-sm text-stone-500 mt-2 leading-relaxed">
        Kode permohonan diberikan otomatis setelah Anda mengirim formulir pengajuan surat.
        Bila kode hilang, silakan hubungi kantor pondok dengan menyebutkan nama dan tanggal pengajuan.
      </p>
      <div class="mt-5 flex flex-wrap gap-3">
        <a href="{{ route('surat.create') }}"
           class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-emerald-700 text-white text-sm font-extrabold hover:bg-emerald-800 transition">
          <i class="fa-solid fa-pen-to-square"></i> Ajukan Surat
        </a>
        @if($waUrl)
          <a href="{{ $waUrl }}" target="_blank" rel="noopener"
             class="inline-flex items-center gap-2 px-5 py-3 rounded-xl border border-stone-200 text-stone-600 text-sm font-extrabold hover:bg-stone-50 transition">
            <i class="fa-brands fa-whatsapp text-emerald-600"></i> Hubungi Kantor
          </a>
        @endif
      </div>
    </div>
  </div>
@endif
@endsection

@push('scripts')
<script>
  (function () {
    const input = document.getElementById('kode');
    if (!input) return;
    input.addEventListener('input', function () {
      input.value = input.value.toUpperCase().replace(/[^A-Z0-9-]/g, '');
    });
  })();
</script>
@endpush
