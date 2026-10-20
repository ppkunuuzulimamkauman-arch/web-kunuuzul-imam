@extends('layouts.alumni')

@section('title', 'Lengkapi Biodata Alumni')
@section('meta_description', 'Lengkapi biodata alumni PP Kunuuzul Imam Kauman.')
@section('robots', 'noindex, nofollow')

@section('content')
@php
    $selectedPekerjaan = (string) old('pekerjaan', '');
    $pekerjaanIsOther = $selectedPekerjaan === '__other__'
        || ($selectedPekerjaan !== '' && ! in_array($selectedPekerjaan, $pekerjaanOptions, true));
    $pekerjaanOtherValue = $selectedPekerjaan === '__other__'
        ? (string) old('pekerjaan_lainnya', '')
        : ($pekerjaanIsOther ? $selectedPekerjaan : '');

    $inputClass = 'mt-1.5 w-full px-4 py-3 rounded-xl border border-stone-200 bg-stone-50 text-sm text-stone-900 placeholder-stone-400 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/30 transition';
    $labelTextClass = 'text-[12px] font-extrabold text-stone-700';
    $sectionTitleClass = 'text-[11px] font-extrabold tracking-[0.2em] text-emerald-700';
@endphp

<section class="bg-emerald-900 text-white">
  <div class="max-w-4xl mx-auto px-4 py-10 md:py-14 text-center">
    <p class="text-[11px] font-extrabold tracking-[0.3em] text-emerald-300">BIODATA ALUMNI</p>
    <h1 class="text-3xl md:text-4xl font-extrabold mt-2">Lengkapi Data Anda</h1>
    <p class="text-emerald-100/80 text-sm mt-3">
      @if($nik !== '')
        NIK <span class="font-mono text-white">{{ $nik }}</span> sudah terisi.
        Lengkapi biodata di bawah ini untuk mendaftar.
      @else
        Isi NIK dan biodata Anda untuk mendaftar sebagai alumni.
      @endif
    </p>
  </div>
</section>

<div class="max-w-4xl mx-auto px-4 py-10">
  <div class="bg-white border border-stone-200 rounded-3xl p-6 md:p-10 shadow-sm">
    <form method="POST" action="{{ route('alumni.register.store') }}" enctype="multipart/form-data"
          class="grid sm:grid-cols-2 gap-x-5 gap-y-4">
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

      <div class="sm:col-span-2 pt-1">
        <p class="{{ $sectionTitleClass }}">DATA DIRI</p>
        <div class="h-px bg-stone-100 mt-2"></div>
      </div>

      <label class="block sm:col-span-2">
        <span class="{{ $labelTextClass }}">NIK *</span>
        <input id="nik" name="nik" required inputmode="numeric" autocomplete="off" maxlength="16"
               pattern="[0-9]{16}" value="{{ old('nik', $nik) }}" placeholder="16 digit angka"
               class="{{ $inputClass }} font-mono tracking-wider">
        <span class="block text-[12px] text-stone-400 mt-1">16 digit angka pada KTP/KK.</span>
        @error('nik')
          <span class="block text-red-600 text-[12px] font-bold mt-1">{{ $message }}</span>
        @enderror
      </label>

      <label class="block sm:col-span-2">
        <span class="{{ $labelTextClass }}">Nama lengkap *</span>
        <input name="nama" required value="{{ old('nama') }}" autocomplete="name" class="{{ $inputClass }}">
      </label>

      <label class="block">
        <span class="{{ $labelTextClass }}">Jenis kelamin *</span>
        <select name="jenis_kelamin" required class="{{ $inputClass }}">
          <option value="">Pilih jenis kelamin</option>
          @foreach(\App\Models\Alumni::jenisKelaminOptions() as $value => $label)
            <option value="{{ $value }}" @selected(old('jenis_kelamin') === $value)>{{ $label }}</option>
          @endforeach
        </select>
      </label>

      <label class="block">
        <span class="{{ $labelTextClass }}">Status *</span>
        <select name="status_hidup" required class="{{ $inputClass }}">
          @foreach(\App\Models\Alumni::statusHidupOptions() as $value => $label)
            <option value="{{ $value }}" @selected(old('status_hidup', 'hidup') === $value)>{{ $label }}</option>
          @endforeach
        </select>
        <span class="block text-[12px] text-stone-400 mt-1">Status hidup / meninggal.</span>
      </label>

      <label class="block">
        <span class="{{ $labelTextClass }}">Tempat lahir *</span>
        <input name="tempat_lahir" required value="{{ old('tempat_lahir') }}" placeholder="Contoh: Bondowoso" class="{{ $inputClass }}">
      </label>

      <label class="block">
        <span class="{{ $labelTextClass }}">Tanggal lahir *</span>
        <input type="date" name="tanggal_lahir" required max="{{ now()->format('Y-m-d') }}"
               value="{{ old('tanggal_lahir') }}" class="{{ $inputClass }}">
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

      <div class="sm:col-span-2 pt-3">
        <p class="{{ $sectionTitleClass }}">ALAMAT LENGKAP</p>
        <div class="h-px bg-stone-100 mt-2"></div>
      </div>

      <label class="block">
        <span class="{{ $labelTextClass }}">Provinsi *</span>
        <input name="provinsi" required value="{{ old('provinsi', 'Jawa Timur') }}" placeholder="Contoh: Jawa Timur" class="{{ $inputClass }}">
      </label>

      <label class="block">
        <span class="{{ $labelTextClass }}">Kabupaten *</span>
        <input id="kabupaten" name="kabupaten" required
               value="{{ old('kabupaten') }}" placeholder="Contoh: Bondowoso / Jember" class="{{ $inputClass }}">
      </label>

      <label class="block">
        <span class="{{ $labelTextClass }}">Kecamatan *</span>
        <input id="kecamatan" name="kecamatan" required
               value="{{ old('kecamatan') }}" placeholder="Contoh: Binakal" class="{{ $inputClass }}">
      </label>

      <label class="block">
        <span class="{{ $labelTextClass }}">Desa / kelurahan *</span>
        <input name="desa" required value="{{ old('desa') }}" placeholder="Nama desa / kelurahan" class="{{ $inputClass }}">
      </label>

      <label class="block sm:col-span-2">
        <span class="{{ $labelTextClass }}">Dusun *</span>
        <input name="dusun" required value="{{ old('dusun') }}" placeholder="Nama dusun" class="{{ $inputClass }}">
      </label>

      <label class="block">
        <span class="{{ $labelTextClass }}">RT *</span>
        <input name="rt" required inputmode="numeric" maxlength="3" pattern="[0-9]{1,3}"
               value="{{ old('rt') }}" placeholder="Contoh: 001" class="{{ $inputClass }}">
      </label>

      <label class="block">
        <span class="{{ $labelTextClass }}">RW *</span>
        <input name="rw" required inputmode="numeric" maxlength="3" pattern="[0-9]{1,3}"
               value="{{ old('rw') }}" placeholder="Contoh: 002" class="{{ $inputClass }}">
      </label>

      <label class="block sm:col-span-2">
        <span class="{{ $labelTextClass }}">Wilayah PK</span>
        <input id="wilayah-pk-preview" readonly
               placeholder="Otomatis terisi dari kabupaten dan kecamatan"
               style="background-color:#ecfdf5;color:#065f46"
               class="{{ $inputClass }} font-bold">
        <span class="block text-[12px] text-stone-400 mt-1">
          Wilayah PK ditentukan otomatis. Jika alamat di luar wilayah PK 1–7, kolom ini akan kosong.
        </span>
      </label>

      <div class="sm:col-span-2 pt-3">
        <p class="{{ $sectionTitleClass }}">KONTAK &amp; PEKERJAAN</p>
        <div class="h-px bg-stone-100 mt-2"></div>
      </div>

      <label class="block">
        <span class="{{ $labelTextClass }}">No. HP / WhatsApp *</span>
        <input name="no_hp" required value="{{ old('no_hp') }}" inputmode="tel" autocomplete="tel"
               placeholder="0812..." class="{{ $inputClass }}">
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

      <div class="sm:col-span-2 pt-3">
        <p class="{{ $sectionTitleClass }}">FOTO &amp; VERIFIKASI</p>
        <div class="h-px bg-stone-100 mt-2"></div>
      </div>

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
        <a href="{{ route('alumni.home') }}"
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

    bindOther('pekerjaan-select', 'pekerjaan-other');

    const nik = document.getElementById('nik');
    if (nik) {
      nik.addEventListener('input', function () {
        this.value = this.value.replace(/\D/g, '').slice(0, 16);
      });
    }

    const kabupaten = document.getElementById('kabupaten');
    const kecamatan = document.getElementById('kecamatan');
    const preview = document.getElementById('wilayah-pk-preview');
    const map = @json($wilayahPkMap);

    function normalize(value) {
      return (value || '').toLowerCase()
        .replace(/\b(kecamatan|kec|kabupaten|kab|kota)\b/g, ' ')
        .replace(/[^a-z0-9]+/g, ' ')
        .trim();
    }

    function syncWilayahPk() {
      if (!preview) return;

      const kab = normalize(kabupaten ? kabupaten.value : '');
      const kec = normalize(kecamatan ? kecamatan.value : '');
      let pk = '';

      if (kab === 'jember') {
        pk = 'PK 2';
      } else if (kab === 'bondowoso' && map[kec]) {
        pk = map[kec];
      }

      preview.value = pk ? 'Wilayah ' + pk : '';
    }

    [kabupaten, kecamatan].forEach(function (element) {
      if (!element) return;
      element.addEventListener('input', syncWilayahPk);
      element.addEventListener('change', syncWilayahPk);
    });
    syncWilayahPk();
  })();
</script>
@endpush
