@extends('layouts.surat')

@section('title', 'Permohonan Terkirim')
@section('robots', 'noindex, nofollow')

@section('content')
@php
    $waDigits = preg_replace('/\D+/', '', (string) $waKontak);
    if (str_starts_with($waDigits, '0')) {
        $waDigits = '62'.substr($waDigits, 1);
    }
    $waUrl = strlen($waDigits) >= 10 && ! str_contains((string) $waKontak, 'x') ? 'https://wa.me/'.$waDigits : null;
    $waPesan = rawurlencode("Assalamu'alaikum, saya sudah mengajukan surat dengan kode {$permohonan->kode} atas nama {$permohonan->nama}. Mohon dibantu pengecekannya. Terima kasih.");
@endphp

<section class="bg-emerald-900 text-white">
  <div class="max-w-3xl mx-auto px-4 py-12 md:py-16 text-center">
    <div class="w-16 h-16 mx-auto rounded-full bg-white/10 border border-white/20 flex items-center justify-center text-2xl">
      <i class="fa-solid fa-circle-check text-emerald-300"></i>
    </div>
    <h1 class="text-3xl md:text-4xl font-extrabold mt-5">Permohonan Terkirim</h1>
    <p class="text-emerald-100/80 text-sm mt-3 max-w-xl mx-auto">
      Simpan kode di bawah ini. Gunakan kode tersebut untuk mengecek status permohonan surat Anda.
    </p>
  </div>
</section>

<div class="max-w-3xl mx-auto px-4 py-10">
  <div class="bg-white border border-stone-200 rounded-3xl shadow-sm overflow-hidden">
    <div class="p-6 md:p-8 text-center border-b border-stone-100">
      <p class="text-[10px] font-extrabold tracking-[0.25em] text-stone-400">KODE PERMOHONAN</p>
      <div class="mt-2 flex flex-col sm:flex-row items-center justify-center gap-3">
        <p id="kode" class="text-2xl md:text-3xl font-extrabold tracking-[0.12em] text-emerald-800 font-mono">{{ $permohonan->kode }}</p>
        <button type="button" id="copy-kode"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-stone-100 text-stone-700 text-[12px] font-extrabold hover:bg-stone-200 transition">
          <i class="fa-regular fa-copy"></i> Salin
        </button>
      </div>
      <p class="text-[12px] text-stone-400 mt-3">Tulis atau foto kode ini agar tidak lupa.</p>
    </div>

    <dl class="p-6 md:p-8 grid sm:grid-cols-2 gap-x-6 gap-y-4 text-sm">
      <div>
        <dt class="text-[11px] font-extrabold tracking-[0.15em] text-stone-400">PEMOHON</dt>
        <dd class="font-bold text-stone-800 mt-1">{{ $permohonan->nama }}</dd>
      </div>
      <div>
        <dt class="text-[11px] font-extrabold tracking-[0.15em] text-stone-400">JENIS SURAT</dt>
        <dd class="font-bold text-stone-800 mt-1">{{ $permohonan->jenisLabel() }}</dd>
      </div>
      <div>
        <dt class="text-[11px] font-extrabold tracking-[0.15em] text-stone-400">TANGGAL AJUAN</dt>
        <dd class="font-bold text-stone-800 mt-1">{{ $permohonan->created_at?->locale('id')->translatedFormat('d F Y, H:i') }}</dd>
      </div>
      <div>
        <dt class="text-[11px] font-extrabold tracking-[0.15em] text-stone-400">STATUS</dt>
        <dd class="mt-1">
          <span class="inline-flex items-center gap-1.5 text-[11px] font-extrabold px-2.5 py-1 rounded-full bg-amber-100 text-amber-800">
            <i class="fa-solid fa-clock"></i> {{ $permohonan->statusLabel() }}
          </span>
        </dd>
      </div>
    </dl>

    <div class="px-6 md:px-8 pb-6 md:pb-8">
      <div class="bg-stone-50 border border-stone-200 rounded-2xl p-5">
        <p class="font-extrabold text-stone-900 text-sm">Langkah berikutnya</p>
        <ol class="mt-3 space-y-2 text-[13px] text-stone-600 list-decimal list-inside">
          <li>Petugas pondok akan memverifikasi permohonan Anda.</li>
          <li>Pantau status secara berkala melalui halaman Cek Status.</li>
          <li>Jika surat sudah selesai, Anda dapat mengunduh atau mengambilnya sesuai petunjuk admin.</li>
        </ol>
      </div>

      <div class="mt-6 flex flex-wrap gap-3">
        <a href="{{ route('surat.cek', ['kode' => $permohonan->kode]) }}"
           class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-emerald-700 text-white text-sm font-extrabold hover:bg-emerald-800 transition">
          <i class="fa-solid fa-magnifying-glass"></i> Cek Status
        </a>
        @if($waUrl)
          <a href="{{ $waUrl }}?text={{ $waPesan }}" target="_blank" rel="noopener"
             class="inline-flex items-center gap-2 px-5 py-3 rounded-xl border border-stone-200 text-stone-700 text-sm font-extrabold hover:bg-stone-50 transition">
            <i class="fa-brands fa-whatsapp text-emerald-600"></i> Konfirmasi via WhatsApp
          </a>
        @endif
        <a href="{{ route('surat.home') }}"
           class="inline-flex items-center gap-2 px-5 py-3 rounded-xl border border-stone-200 text-stone-600 text-sm font-extrabold hover:bg-stone-50 transition">
          Kembali ke Beranda
        </a>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  (function () {
    const btn = document.getElementById('copy-kode');
    const kode = document.getElementById('kode');
    if (!btn || !kode) return;

    btn.addEventListener('click', async function () {
      const text = kode.textContent.trim();
      try {
        await navigator.clipboard.writeText(text);
      } catch (e) {
        const range = document.createRange();
        range.selectNodeContents(kode);
        const sel = window.getSelection();
        sel.removeAllRanges();
        sel.addRange(range);
        document.execCommand('copy');
        sel.removeAllRanges();
      }
      const original = btn.innerHTML;
      btn.innerHTML = '<i class="fa-solid fa-check"></i> Tersalin';
      setTimeout(function () { btn.innerHTML = original; }, 1800);
    });
  })();
</script>
@endpush
