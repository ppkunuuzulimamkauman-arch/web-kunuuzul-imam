@extends('layouts.alumni')

@section('title', 'Pendaftaran Alumni')
@section('meta_description', 'Masukkan NIK Anda untuk mendaftar atau melihat data alumni PP Kunuuzul Imam Kauman.')

@section('content')
<style>
  dialog::backdrop { background: rgba(28, 25, 23, .55); }
</style>

<section class="bg-emerald-900 text-white">
  <div class="max-w-7xl mx-auto px-4 py-12 md:py-16 text-center">
    <p class="text-[11px] font-extrabold tracking-[0.3em] text-emerald-300">IKATAN ALUMNI</p>
    <h1 class="text-3xl md:text-5xl font-extrabold mt-3">Pendaftaran Alumni</h1>
    <p class="text-emerald-100/80 mt-3 text-sm md:text-base">Sensus Alumni PP Kunuuzul Imam Kauman</p>
    <p class="text-emerald-100/70 mt-4 max-w-xl mx-auto text-sm leading-relaxed">
      Masukkan NIK Anda untuk mendaftar atau melihat data alumni yang sudah tercatat.
    </p>

    <div class="mt-7">
      <p class="text-4xl md:text-5xl font-extrabold text-emerald-300" data-total-alumni="{{ $totalAlumni }}">{{ $totalAlumni }}</p>
      <p class="text-xs text-emerald-100/70 mt-1">Alumni sudah terdaftar</p>
    </div>
  </div>
</section>

<div class="max-w-xl mx-auto px-4 -mt-6 md:-mt-8 relative z-10">
  <div class="bg-white border border-stone-200 rounded-3xl p-6 md:p-8 shadow-lg shadow-stone-200/60">
    <div class="flex items-center justify-between gap-3">
      <label for="nik" class="text-[12px] font-extrabold text-stone-700">NIK</label>
      <button type="button" onclick="document.getElementById('nik-help').showModal()"
              class="text-[11px] font-bold text-emerald-700 hover:text-emerald-800 transition">
        Contoh lokasi NIK
      </button>
    </div>

    <form method="POST" action="{{ route('alumni.register.check') }}" class="mt-2">
      @csrf
      <input id="nik" name="nik" inputmode="numeric" autocomplete="off" maxlength="16"
             pattern="[0-9]{16}" required value="{{ old('nik') }}" placeholder="16 digit angka"
             class="w-full px-4 py-3.5 rounded-xl border border-stone-200 bg-stone-50 text-sm text-stone-900 placeholder-stone-400 font-mono tracking-wider focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/30 transition">
      @error('nik')
        <p class="text-red-600 text-[12px] font-bold mt-2">{{ $message }}</p>
      @enderror
      <button type="submit"
              class="mt-3 w-full px-5 py-3.5 rounded-xl bg-emerald-700 text-white text-sm font-extrabold hover:bg-emerald-800 transition">
        Lanjut
      </button>
    </form>

    <p class="text-[11px] text-stone-400 mt-3">
      NIK terdapat pada KTP/KK. Jika belum memiliki NIK, silakan hubungi pengurus pesantren.
    </p>
  </div>
</div>

<div class="max-w-2xl mx-auto px-4 py-10 md:py-12 text-center">
  <blockquote class="text-stone-700 italic text-sm md:text-base leading-relaxed">
    "Tidak ada yang namanya mantan santri. Sampai kapanpun, santri tetaplah santri."
  </blockquote>
  <p class="mt-5 text-stone-500 text-xs md:text-sm leading-relaxed">
    Pendataan ini bukan sekadar administrasi, melainkan jembatan silaturahmi: agar kita tetap
    saling mengenal, saling mendoakan, dan tetap tersambung dengan keluarga besar Pondok Pesantren
    Kunuuzul Imam Kauman — di mana pun kita mengabdi dan berkarya.
  </p>
</div>

<dialog id="nik-help" class="bg-transparent p-0 m-auto max-w-md w-[calc(100%-2rem)]">
  <div class="bg-white border border-stone-200 rounded-2xl p-5 text-left text-stone-800 shadow-2xl">
    <div class="flex items-start justify-between gap-4">
      <div>
        <h2 class="font-extrabold text-stone-900">Contoh lokasi NIK</h2>
        <p class="text-xs text-stone-500 mt-1">NIK adalah 16 digit angka yang tertera di KTP, KK, atau SIM.</p>
      </div>
      <button type="button" onclick="document.getElementById('nik-help').close()"
              class="text-xl leading-none text-stone-400 hover:text-stone-700 transition" aria-label="Tutup">
        &times;
      </button>
    </div>

    <div class="mt-4 rounded-xl bg-gradient-to-br from-stone-100 to-stone-200 text-stone-900 p-4">
      <div class="flex items-center justify-between">
        <p class="text-[9px] font-extrabold tracking-wider">PROVINSI JAWA TIMUR</p>
        <p class="text-[9px] font-extrabold">KTP</p>
      </div>
      <p class="text-[9px] font-extrabold mt-1">KABUPATEN JOMBANG</p>
      <p class="text-[10px] font-extrabold mt-2">NIK</p>
      <p class="font-mono text-lg font-extrabold tracking-wider bg-amber-200/70 rounded px-1 inline-block">3517xxxxxxxxxxxx</p>
      <div class="mt-2 space-y-0.5 text-[9px]">
        <p><span class="inline-block w-20 text-stone-500">Nama</span> : ALUMNI KUNUUZUL</p>
        <p><span class="inline-block w-20 text-stone-500">Tempat/Tgl Lahir</span> : JOMBANG, 01-01-2001</p>
      </div>
    </div>

    <p class="text-[11px] text-stone-500 mt-3">Angka NIK biasanya tercetak di bawah nama provinsi/kabupaten pada KTP.</p>
    <button type="button" onclick="document.getElementById('nik-help').close()"
            class="mt-4 w-full rounded-xl bg-emerald-700 px-4 py-2.5 text-sm font-extrabold text-white hover:bg-emerald-800 transition">
      Mengerti
    </button>
  </div>
</dialog>
@endsection

@push('scripts')
<script>
  (function () {
    const input = document.getElementById('nik');
    if (!input) return;
    input.addEventListener('input', function () {
      input.value = input.value.replace(/\D/g, '').slice(0, 16);
    });
  })();
</script>
@endpush
