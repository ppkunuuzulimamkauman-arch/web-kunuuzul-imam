@extends('layouts.alumni')

@section('title', 'Lengkapi Biodata Alumni')
@section('meta_description', 'Lengkapi biodata alumni PP Kunuuzul Imam Kauman.')
@section('robots', 'noindex, nofollow')

@section('content')
@php
    $selectedLembaga = (string) old('lembaga', '');
    $lembagaIsOther = $selectedLembaga === '__other__'
        || ($selectedLembaga !== '' && ! in_array($selectedLembaga, $lembagaOptions, true));
    $lembagaOtherValue = $selectedLembaga === '__other__'
        ? (string) old('lembaga_lainnya', '')
        : ($lembagaIsOther ? $selectedLembaga : '');

    $selectedPekerjaan = (string) old('pekerjaan', '');
    $pekerjaanIsOther = $selectedPekerjaan === '__other__'
        || ($selectedPekerjaan !== '' && ! in_array($selectedPekerjaan, $pekerjaanOptions, true));
    $pekerjaanOtherValue = $selectedPekerjaan === '__other__'
        ? (string) old('pekerjaan_lainnya', '')
        : ($pekerjaanIsOther ? $selectedPekerjaan : '');

    $inputClass = 'mt-1.5 w-full px-4 py-3 rounded-xl border border-stone-200 bg-stone-50 text-sm text-stone-900 placeholder-stone-400 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/30 transition';
    $labelTextClass = 'text-[12px] font-extrabold text-stone-700';
@endphp

<section class="bg-emerald-900 text-white">
  <div class="max-w-4xl mx-auto px-4 py-10 md:py-14 text-center">
    <p class="text-[11px] font-extrabold tracking-[0.3em] text-emerald-300">BIODATA ALUMNI</p>
    <h1 class="text-3xl md:text-4xl font-extrabold mt-2">Lengkapi Data Anda</h1>
    <p class="text-emerald-100/80 text-sm mt-3">
      NIK <span class="font-mono text-white">{{ $nik }}</span> belum terdaftar.
      Isi biodata di bawah ini untuk mendaftar.
    </p>
  </div>
</section>

<div class="max-w-4xl mx-auto px-4 py-10">
  <div class="bg-white border border-stone-200 rounded-3xl p-6 md:p-10 shadow-sm">
    <form method="POST" action="{{ route('alumni.register.store') }}" enctype="multipart/form-data"
          class="grid sm:grid-cols-2 gap-5">
      @csrf

      @if($errors->any())
        <div class="sm:col-span-2 bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 text-sm">
          <p class="font-extrabold">Periksa kembali isian Anda:</p>
          <ul class="list-disc list-inside mt-1 space-y-1">
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <div class="sm:col-span-2 rounded-xl border border-emerald-100 bg-emerald-50 px-4 py-3 flex items-center justify-between gap-3">
        <div>
          <p class="text-[10px] font-extrabold tracking-wider text-emerald-700">NIK</p>
          <p class="font-mono text-sm text-emerald-950 mt-1">{{ $nik }}</p>
        </div>
        <a href="{{ route('alumni.register.reset') }}" class="text-[11px] font-bold text-emerald-700 hover:text-emerald-800">Ganti NIK</a>
      </div>

      <label class="block sm:col-span-2">
        <span class="{{ $labelTextClass }}">Nama lengkap *</span>
        <input name="nama" required value="{{ old('nama') }}" autocomplete="name" class="{{ $inputClass }}">
      </label>

      <label class="block">
        <span class="{{ $labelTextClass }}">Angkatan (tahun masuk)</span>
        <select name="angkatan" class="{{ $inputClass }}">
          <option value="">Pilih tahun</option>
          @foreach($years as $year)
            <option value="{{ $year }}" @selected((string) old('angkatan') === (string) $year)>{{ $year }}</option>
          @endforeach
        </select>
      </label>

      <label class="block">
        <span class="{{ $labelTextClass }}">Tahun lulus</span>
        <select name="tahun_lulus" class="{{ $inputClass }}">
          <option value="">Pilih tahun</option>
          @foreach($years as $year)
            <option value="{{ $year }}" @selected((string) old('tahun_lulus') === (string) $year)>{{ $year }}</option>
          @endforeach
        </select>
      </label>

      <label class="block">
        <span class="{{ $labelTextClass }}">Lembaga</span>
        <select name="lembaga" id="lembaga-select" class="{{ $inputClass }}">
          <option value="">Pilih lembaga</option>
          @foreach($lembagaOptions as $option)
            <option value="{{ $option }}" @selected(! $lembagaIsOther && $selectedLembaga === $option)>{{ $option }}</option>
          @endforeach
          <option value="__other__" @selected($lembagaIsOther)>Lainnya</option>
        </select>
        <input type="text" name="lembaga_lainnya" id="lembaga-other" value="{{ $lembagaOtherValue }}"
               placeholder="Tulis nama lembaga"
               class="{{ $inputClass }} {{ $lembagaIsOther ? '' : 'hidden' }}">
      </label>

      <label class="block">
        <span class="{{ $labelTextClass }}">Domisili</span>
        <input name="domisili" list="domisili-options" value="{{ old('domisili') }}"
               placeholder="Kota / kabupaten" class="{{ $inputClass }}">
        <datalist id="domisili-options">
          @foreach($domisiliOptions as $option)
            <option value="{{ $option }}"></option>
          @endforeach
        </datalist>
      </label>

      <label class="block">
        <span class="{{ $labelTextClass }}">Pekerjaan</span>
        <select name="pekerjaan" id="pekerjaan-select" class="{{ $inputClass }}">
          <option value="">Pilih pekerjaan</option>
          @foreach($pekerjaanOptions as $option)
            <option value="{{ $option }}" @selected(! $pekerjaanIsOther && $selectedPekerjaan === $option)>{{ $option }}</option>
          @endforeach
          <option value="__other__" @selected($pekerjaanIsOther)>Lainnya</option>
        </select>
        <input type="text" name="pekerjaan_lainnya" id="pekerjaan-other" value="{{ $pekerjaanOtherValue }}"
               placeholder="Tulis pekerjaan"
               class="{{ $inputClass }} {{ $pekerjaanIsOther ? '' : 'hidden' }}">
      </label>

      <label class="block">
        <span class="{{ $labelTextClass }}">No. HP / WhatsApp *</span>
        <input name="no_hp" required value="{{ old('no_hp') }}" inputmode="tel" autocomplete="tel"
               placeholder="0812..." class="{{ $inputClass }}">
      </label>

      <label class="block sm:col-span-2">
        <span class="{{ $labelTextClass }}">Email</span>
        <input type="email" name="email" value="{{ old('email') }}" autocomplete="email" class="{{ $inputClass }}">
      </label>

      <label class="block sm:col-span-2">
        <span class="{{ $labelTextClass }}">Tentang / catatan</span>
        <textarea name="tentang" rows="4" class="{{ $inputClass }}">{{ old('tentang') }}</textarea>
      </label>

      <label class="block sm:col-span-2">
        <span class="{{ $labelTextClass }}">Foto</span>
        <input type="file" name="foto" accept="image/*"
               class="mt-1.5 block w-full text-sm text-stone-500 file:mr-4 file:px-4 file:py-2.5 file:rounded-xl file:border-0 file:bg-emerald-50 file:text-emerald-700 file:text-sm file:font-extrabold hover:file:bg-emerald-100">
        <span class="block text-[12px] text-stone-400 mt-1">Opsional, maksimal 2MB.</span>
      </label>

      <div class="sm:col-span-2">
        @include('partials.turnstile')
      </div>

      <div class="sm:col-span-2 flex flex-wrap gap-3 pt-2">
        <button type="submit"
                class="px-6 py-3 rounded-xl bg-emerald-700 text-white text-sm font-extrabold hover:bg-emerald-800 transition">
          Kirim Pendaftaran
        </button>
        <a href="{{ route('alumni.register') }}"
           class="px-6 py-3 rounded-xl border border-stone-200 text-stone-600 text-sm font-extrabold hover:bg-stone-50 transition">
          Batal
        </a>
      </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script>
  (function () {
    function bindOther(selectId, inputId) {
      const select = document.getElementById(selectId);
      const input = document.getElementById(inputId);
      if (!select || !input) return;

      const sync = function () {
        const isOther = select.value === '__other__';
        input.classList.toggle('hidden', !isOther);
        if (isOther) input.focus();
      };

      select.addEventListener('change', sync);
    }

    bindOther('lembaga-select', 'lembaga-other');
    bindOther('pekerjaan-select', 'pekerjaan-other');
  })();
</script>
@endpush
