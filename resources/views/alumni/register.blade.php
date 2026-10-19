@extends('layouts.alumni')

@section('title', 'Pendaftaran Alumni')
@section('meta_description', 'Daftarkan diri Anda sebagai alumni PP Kunuuzul Imam Kauman. Data akan diverifikasi sebelum tampil di direktori.')

@section('content')
<section class="bg-emerald-900 text-white">
  <div class="max-w-7xl mx-auto px-4 py-12 md:py-16">
    <p class="text-[11px] font-extrabold tracking-[0.3em] text-emerald-300">IKATAN ALUMNI</p>
    <h1 class="text-3xl md:text-4xl font-extrabold mt-2">Pendaftaran Alumni</h1>
    <p class="text-emerald-100/80 mt-3 max-w-2xl text-sm md:text-base leading-relaxed">
      Isi data Anda dengan lengkap. Setelah dikirim, data akan diverifikasi oleh admin
      sebelum tampil di direktori alumni.
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

    <form method="POST" action="{{ route('alumni.register.store') }}" enctype="multipart/form-data"
          class="grid sm:grid-cols-2 gap-5">
      @csrf

      <label class="block sm:col-span-2">
        <span class="text-[12px] font-extrabold text-stone-600">Nama lengkap *</span>
        <input name="nama" required value="{{ old('nama') }}" autocomplete="name"
               class="mt-1.5 w-full px-4 py-3 rounded-xl border border-stone-200 bg-stone-50 text-sm text-stone-800 focus:outline-none focus:border-emerald-600 focus:bg-white transition">
      </label>

      <label class="block">
        <span class="text-[12px] font-extrabold text-stone-600">Angkatan (tahun masuk)</span>
        <input type="number" name="angkatan" min="1900" max="2100" value="{{ old('angkatan') }}"
               placeholder="Mis. 2015"
               class="mt-1.5 w-full px-4 py-3 rounded-xl border border-stone-200 bg-stone-50 text-sm text-stone-800 focus:outline-none focus:border-emerald-600 focus:bg-white transition">
      </label>

      <label class="block">
        <span class="text-[12px] font-extrabold text-stone-600">Tahun lulus</span>
        <input type="number" name="tahun_lulus" min="1900" max="2100" value="{{ old('tahun_lulus') }}"
               placeholder="Mis. 2019"
               class="mt-1.5 w-full px-4 py-3 rounded-xl border border-stone-200 bg-stone-50 text-sm text-stone-800 focus:outline-none focus:border-emerald-600 focus:bg-white transition">
      </label>

      <label class="block">
        <span class="text-[12px] font-extrabold text-stone-600">Lembaga</span>
        <input name="lembaga" value="{{ old('lembaga') }}" placeholder="Mis. MTs, MA, Tahfidz"
               class="mt-1.5 w-full px-4 py-3 rounded-xl border border-stone-200 bg-stone-50 text-sm text-stone-800 focus:outline-none focus:border-emerald-600 focus:bg-white transition">
      </label>

      <label class="block">
        <span class="text-[12px] font-extrabold text-stone-600">Domisili</span>
        <input name="domisili" value="{{ old('domisili') }}" placeholder="Kota / kabupaten"
               class="mt-1.5 w-full px-4 py-3 rounded-xl border border-stone-200 bg-stone-50 text-sm text-stone-800 focus:outline-none focus:border-emerald-600 focus:bg-white transition">
      </label>

      <label class="block">
        <span class="text-[12px] font-extrabold text-stone-600">Pekerjaan</span>
        <input name="pekerjaan" value="{{ old('pekerjaan') }}"
               class="mt-1.5 w-full px-4 py-3 rounded-xl border border-stone-200 bg-stone-50 text-sm text-stone-800 focus:outline-none focus:border-emerald-600 focus:bg-white transition">
      </label>

      <label class="block">
        <span class="text-[12px] font-extrabold text-stone-600">No. HP / WhatsApp *</span>
        <input name="no_hp" required value="{{ old('no_hp') }}" inputmode="tel" autocomplete="tel"
               placeholder="0812..."
               class="mt-1.5 w-full px-4 py-3 rounded-xl border border-stone-200 bg-stone-50 text-sm text-stone-800 focus:outline-none focus:border-emerald-600 focus:bg-white transition">
      </label>

      <label class="block sm:col-span-2">
        <span class="text-[12px] font-extrabold text-stone-600">Email</span>
        <input type="email" name="email" value="{{ old('email') }}" autocomplete="email"
               class="mt-1.5 w-full px-4 py-3 rounded-xl border border-stone-200 bg-stone-50 text-sm text-stone-800 focus:outline-none focus:border-emerald-600 focus:bg-white transition">
      </label>

      <label class="block sm:col-span-2">
        <span class="text-[12px] font-extrabold text-stone-600">Tentang / catatan</span>
        <textarea name="tentang" rows="4"
                  class="mt-1.5 w-full px-4 py-3 rounded-xl border border-stone-200 bg-stone-50 text-sm text-stone-800 focus:outline-none focus:border-emerald-600 focus:bg-white transition">{{ old('tentang') }}</textarea>
      </label>

      <label class="block sm:col-span-2">
        <span class="text-[12px] font-extrabold text-stone-600">Foto</span>
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
