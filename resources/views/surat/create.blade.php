@extends('layouts.surat')

@section('title', 'Ajukan Surat')
@section('meta_description', 'Formulir permohonan surat resmi Pondok Pesantren Kunuuzul Imam Kauman.')
@section('robots', 'noindex, nofollow')

@section('content')
@php
    $selectedJenis = (string) old('jenis', request('jenis', ''));
    $isIzin = $selectedJenis === 'izin_pulang';
    $isDitujukan = in_array($selectedJenis, ['rekomendasi', 'pengantar'], true);
    $isLainnya = $selectedJenis === 'keterangan_lain';

    $inputClass = 'mt-1.5 w-full px-4 py-3 rounded-xl border border-stone-200 bg-stone-50 text-sm text-stone-900 placeholder-stone-400 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/30 transition';
    $labelClass = 'text-[12px] font-extrabold text-stone-700';
@endphp

<section class="bg-emerald-900 text-white">
  <div class="max-w-4xl mx-auto px-4 py-10 md:py-14 text-center">
    <p class="text-[11px] font-extrabold tracking-[0.3em] text-emerald-300">FORMULIR PERMOHONAN</p>
    <h1 class="text-3xl md:text-4xl font-extrabold mt-2">Ajukan Surat</h1>
    <p class="text-emerald-100/80 text-sm mt-3 max-w-xl mx-auto">
      Isi data dengan benar. Setelah terkirim, Anda akan menerima kode permohonan untuk memantau status surat.
    </p>
  </div>
</section>

<div class="max-w-4xl mx-auto px-4 py-10">
  <div class="bg-white border border-stone-200 rounded-3xl p-6 md:p-10 shadow-sm">
    @if($errors->any())
      <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 text-sm mb-6">
        <p class="font-extrabold">Periksa kembali isian Anda:</p>
        <ul class="list-disc list-inside mt-1 space-y-1">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('surat.store') }}" enctype="multipart/form-data" class="space-y-8">
      @csrf

      <div>
        <h2 class="font-extrabold text-stone-900 text-base flex items-center gap-2">
          <span class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center text-[11px] font-extrabold">1</span>
          Jenis Surat
        </h2>
        <div class="mt-4">
          <label class="block">
            <span class="{{ $labelClass }}">Pilih jenis surat *</span>
            <select name="jenis" id="jenis" required class="{{ $inputClass }}">
              <option value="">— Pilih jenis surat —</option>
              @foreach($jenisOptions as $key => $label)
                <option value="{{ $key }}" @selected($selectedJenis === $key)>{{ $label }}</option>
              @endforeach
            </select>
          </label>
          @error('jenis')
            <p class="text-red-600 text-[12px] font-bold mt-1">{{ $message }}</p>
          @enderror
          <div id="jenis-hint" class="hidden mt-3 bg-emerald-50 border border-emerald-100 rounded-xl px-4 py-3 text-[12.5px] text-emerald-900"></div>
        </div>
      </div>

      <div class="border-t border-stone-100 pt-7">
        <h2 class="font-extrabold text-stone-900 text-base flex items-center gap-2">
          <span class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center text-[11px] font-extrabold">2</span>
          Data Pemohon
        </h2>

        <div class="grid sm:grid-cols-2 gap-5 mt-4">
          <label class="block sm:col-span-2">
            <span class="{{ $labelClass }}">Nama lengkap *</span>
            <input name="nama" required value="{{ old('nama') }}" autocomplete="name" class="{{ $inputClass }}">
            @error('nama')<span class="block text-red-600 text-[12px] font-bold mt-1">{{ $message }}</span>@enderror
          </label>

          <label class="block">
            <span class="{{ $labelClass }}">NIK (opsional)</span>
            <input name="nik" inputmode="numeric" maxlength="16" pattern="[0-9]{16}" value="{{ old('nik') }}"
                   placeholder="16 digit angka" class="{{ $inputClass }} font-mono tracking-wider">
            @error('nik')<span class="block text-red-600 text-[12px] font-bold mt-1">{{ $message }}</span>@enderror
          </label>

          <label class="block">
            <span class="{{ $labelClass }}">No. HP / WhatsApp *</span>
            <input name="no_hp" required inputmode="tel" autocomplete="tel" value="{{ old('no_hp') }}"
                   placeholder="0812..." class="{{ $inputClass }}">
            @error('no_hp')<span class="block text-red-600 text-[12px] font-bold mt-1">{{ $message }}</span>@enderror
          </label>

          <label class="block">
            <span class="{{ $labelClass }}">Email (opsional)</span>
            <input type="email" name="email" value="{{ old('email') }}" autocomplete="email" class="{{ $inputClass }}">
            @error('email')<span class="block text-red-600 text-[12px] font-bold mt-1">{{ $message }}</span>@enderror
          </label>

          <label class="block">
            <span class="{{ $labelClass }}">Lembaga / unit (opsional)</span>
            <input name="lembaga" list="lembaga-options" value="{{ old('lembaga') }}"
                   placeholder="Contoh: MA / Tahfidz" class="{{ $inputClass }}">
            <datalist id="lembaga-options">
              @foreach($lembagaOptions as $option)
                <option value="{{ $option }}"></option>
              @endforeach
            </datalist>
            @error('lembaga')<span class="block text-red-600 text-[12px] font-bold mt-1">{{ $message }}</span>@enderror
          </label>

          <label class="block sm:col-span-2">
            <span class="{{ $labelClass }}">Alamat (opsional)</span>
            <textarea name="alamat" rows="2" class="{{ $inputClass }}" placeholder="Alamat lengkap pemohon">{{ old('alamat') }}</textarea>
            @error('alamat')<span class="block text-red-600 text-[12px] font-bold mt-1">{{ $message }}</span>@enderror
          </label>
        </div>
      </div>

      <div class="border-t border-stone-100 pt-7">
        <h2 class="font-extrabold text-stone-900 text-base flex items-center gap-2">
          <span class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center text-[11px] font-extrabold">3</span>
          Detail Permohonan
        </h2>

        <div class="grid sm:grid-cols-2 gap-5 mt-4">
          <div id="group-izin" class="sm:col-span-2 grid sm:grid-cols-2 gap-5 {{ $isIzin ? '' : 'hidden' }}">
            <label class="block">
              <span class="{{ $labelClass }}">Tanggal mulai *</span>
              <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" class="{{ $inputClass }}">
              @error('tanggal_mulai')<span class="block text-red-600 text-[12px] font-bold mt-1">{{ $message }}</span>@enderror
            </label>
            <label class="block">
              <span class="{{ $labelClass }}">Tanggal selesai *</span>
              <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}" class="{{ $inputClass }}">
              @error('tanggal_selesai')<span class="block text-red-600 text-[12px] font-bold mt-1">{{ $message }}</span>@enderror
            </label>
            <label class="block sm:col-span-2">
              <span class="{{ $labelClass }}">Tujuan izin *</span>
              <input name="tujuan" value="{{ old('tujuan') }}" placeholder="Contoh: Jombang, acara keluarga" class="{{ $inputClass }}">
              @error('tujuan')<span class="block text-red-600 text-[12px] font-bold mt-1">{{ $message }}</span>@enderror
            </label>
          </div>

          <div id="group-ditujukan" class="sm:col-span-2 {{ $isDitujukan ? '' : 'hidden' }}">
            <label class="block">
              <span class="{{ $labelClass }}">Instansi / lembaga tujuan *</span>
              <input name="ditujukan" value="{{ old('ditujukan') }}"
                     placeholder="Contoh: Universitas Hasyim Asy'ari" class="{{ $inputClass }}">
              @error('ditujukan')<span class="block text-red-600 text-[12px] font-bold mt-1">{{ $message }}</span>@enderror
            </label>
          </div>

          <div id="group-lainnya" class="sm:col-span-2 {{ $isLainnya ? '' : 'hidden' }}">
            <label class="block">
              <span class="{{ $labelClass }}">Jenis surat yang dibutuhkan *</span>
              <input name="jenis_lainnya" value="{{ old('jenis_lainnya') }}"
                     placeholder="Contoh: Surat Keterangan Domisili Santri" class="{{ $inputClass }}">
              @error('jenis_lainnya')<span class="block text-red-600 text-[12px] font-bold mt-1">{{ $message }}</span>@enderror
            </label>
          </div>

          <label class="block sm:col-span-2">
            <span class="{{ $labelClass }}">Keperluan surat *</span>
            <textarea name="keperluan" rows="4" required class="{{ $inputClass }}"
                      placeholder="Jelaskan keperluan surat secara singkat dan jelas">{{ old('keperluan') }}</textarea>
            @error('keperluan')<span class="block text-red-600 text-[12px] font-bold mt-1">{{ $message }}</span>@enderror
          </label>
        </div>
      </div>

      <div class="border-t border-stone-100 pt-7">
        <h2 class="font-extrabold text-stone-900 text-base flex items-center gap-2">
          <span class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center text-[11px] font-extrabold">4</span>
          Lampiran & Konfirmasi
        </h2>

        <div class="grid gap-5 mt-4">
          <label class="block">
            <span class="{{ $labelClass }}">Lampiran pendukung (opsional)</span>
            <input type="file" name="lampiran" accept=".pdf,.jpg,.jpeg,.png"
                   class="mt-1.5 block w-full text-sm text-stone-500 file:mr-4 file:px-4 file:py-2.5 file:rounded-xl file:border-0 file:bg-emerald-50 file:text-emerald-700 file:text-sm file:font-extrabold hover:file:bg-emerald-100">
            <span class="block text-[12px] text-stone-400 mt-1">KTP, KK, atau berkas pendukung lain. PDF / JPG / PNG, maksimal 4 MB.</span>
            @error('lampiran')<span class="block text-red-600 text-[12px] font-bold mt-1">{{ $message }}</span>@enderror
          </label>

          <label class="flex items-start gap-3 text-[13px] text-stone-600">
            <input type="checkbox" name="setuju" value="1" required @checked(old('setuju'))
                   class="mt-0.5 w-4 h-4 rounded border-stone-300 text-emerald-700 focus:ring-emerald-500">
            <span>Saya menyatakan data yang saya isi benar dan bersedia dihubungi melalui nomor HP / WhatsApp yang tercantum.</span>
          </label>
          @error('setuju')<span class="block text-red-600 text-[12px] font-bold">{{ $message }}</span>@enderror

          @include('partials.turnstile')
        </div>
      </div>

      <div class="flex flex-wrap gap-3 pt-2 border-t border-stone-100">
        <button type="submit"
                class="px-6 py-3 rounded-xl bg-emerald-700 text-white text-sm font-extrabold hover:bg-emerald-800 transition">
          <i class="fa-solid fa-paper-plane mr-1"></i> Kirim Permohonan
        </button>
        <a href="{{ route('surat.home') }}"
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
    const jenis = document.getElementById('jenis');
    const hint = document.getElementById('jenis-hint');
    const groupIzin = document.getElementById('group-izin');
    const groupDitujukan = document.getElementById('group-ditujukan');
    const groupLainnya = document.getElementById('group-lainnya');

    const meta = @json($jenisMeta);
    const labels = @json($jenisOptions);

    function toggleGroup(el, show, requiredNames) {
      if (!el) return;
      el.classList.toggle('hidden', !show);
      if (requiredNames) {
        requiredNames.forEach(function (name) {
          el.querySelectorAll('[name="' + name + '"]').forEach(function (input) {
            input.required = show;
          });
        });
      }
    }

    function sync() {
      const value = jenis.value;

      toggleGroup(groupIzin, value === 'izin_pulang', ['tanggal_mulai', 'tanggal_selesai', 'tujuan']);
      toggleGroup(groupDitujukan, value === 'rekomendasi' || value === 'pengantar', ['ditujukan']);
      toggleGroup(groupLainnya, value === 'keterangan_lain', ['jenis_lainnya']);

      if (hint) {
        if (value && meta[value]) {
          const syarat = (meta[value].syarat || []).map(function (item) {
            return '<li style="display:flex;gap:6px"><span>•</span><span>' + item + '</span></li>';
          }).join('');
          hint.innerHTML = '<p style="font-weight:800">' + (labels[value] || value) + '</p>'
            + '<p style="margin-top:4px">' + meta[value].desc + '</p>'
            + (syarat ? '<ul style="margin-top:8px;display:grid;gap:2px">' + syarat + '</ul>' : '');
          hint.classList.remove('hidden');
        } else {
          hint.classList.add('hidden');
          hint.innerHTML = '';
        }
      }
    }

    if (jenis) {
      jenis.addEventListener('change', sync);
      sync();
    }

    const nik = document.querySelector('[name="nik"]');
    if (nik) {
      nik.addEventListener('input', function () {
        nik.value = nik.value.replace(/\D/g, '').slice(0, 16);
      });
    }
  })();
</script>
@endpush
