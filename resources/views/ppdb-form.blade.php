@extends('layouts.app')
@section('title', 'Pendaftaran Santri Baru (PSB)')
@section('content')
<div class="bg-white">
<div class="max-w-3xl mx-auto px-4 py-8 md:py-10">
  <p class="text-[12px] font-bold text-stone-400 tracking-wider"><a href="{{ route('home') }}" class="hover:text-orange-600 transition">Beranda</a> <span class="mx-1">/</span> <span class="text-stone-600">PSB</span></p>
  <h1 class="font-extrabold text-stone-900 text-[26px] md:text-[30px] leading-tight mt-1.5">Pendaftaran Santri Baru 2026/2027</h1>
  <div class="w-16 h-1 bg-orange-400 rounded-full mt-3 mb-6"></div>

  {{-- Alur pendaftaran: 4 langkah --}}
  <div class="reveal border border-stone-200 rounded-2xl p-5 mb-5 bg-stone-50">
    <div class="psb-steps grid grid-cols-4 gap-2 text-center">
      <div class="psb-step now"><div class="psb-dot">1</div><p>Isi Formulir</p></div>
      <div class="psb-step"><div class="psb-dot">2</div><p>Upload Berkas</p></div>
      <div class="psb-step"><div class="psb-dot">3</div><p>Verifikasi WA</p></div>
      <div class="psb-step"><div class="psb-dot">4</div><p>Cek Status</p></div>
    </div>
  </div>

  @if($errors->any())<div class="reveal bg-red-50 border border-red-200 text-red-700 rounded-2xl p-4 text-sm mb-4"><ul class="list-disc ml-5">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
  @if(session('sukses'))<div class="reveal bg-green-50 border border-green-200 text-green-800 rounded-2xl p-4 text-sm mb-4">{{ session('sukses') }}</div>@endif
  <form action="{{ route('ppdb.store') }}" method="POST" enctype="multipart/form-data" class="reveal border border-stone-200 rounded-2xl p-6 space-y-4 bg-white shadow-sm">
    @csrf
    <h2 class="font-extrabold text-emerald-950 text-[16px]">Data Santri</h2>
    <div><label class="font-bold text-sm">Nama Lengkap *</label><input name="nama_lengkap" required value="{{ old('nama_lengkap') }}" class="w-full border border-stone-200 rounded-xl px-4 py-2.5 mt-1.5 text-[14px] focus:outline-none focus:border-emerald-700 focus:ring-1 focus:ring-emerald-700 transition" placeholder="Nama sesuai KK"></div>
    <div class="grid md:grid-cols-2 gap-4">
      <div><label class="font-bold text-sm">Jenis Kelamin *</label><select name="jenis_kelamin" required class="w-full border border-stone-200 rounded-xl px-4 py-2.5 mt-1.5 text-[14px] focus:outline-none focus:border-emerald-700 focus:ring-1 focus:ring-emerald-700 transition bg-white"><option value="L" @selected(old('jenis_kelamin')==='L')>Laki-laki</option><option value="P" @selected(old('jenis_kelamin')==='P')>Perempuan</option></select></div>
      <div><label class="font-bold text-sm">NIK *</label><input name="nik" required value="{{ old('nik') }}" maxlength="20" class="w-full border border-stone-200 rounded-xl px-4 py-2.5 mt-1.5 text-[14px] focus:outline-none focus:border-emerald-700 focus:ring-1 focus:ring-emerald-700 transition" placeholder="16 digit sesuai KK"></div>
    </div>
    <div class="grid md:grid-cols-2 gap-4">
      <div><label class="font-bold text-sm">Tempat Lahir *</label><input name="tempat_lahir" required value="{{ old('tempat_lahir') }}" class="w-full border border-stone-200 rounded-xl px-4 py-2.5 mt-1.5 text-[14px] focus:outline-none focus:border-emerald-700 focus:ring-1 focus:ring-emerald-700 transition"></div>
      <div><label class="font-bold text-sm">Tanggal Lahir *</label><input type="date" name="tanggal_lahir" required value="{{ old('tanggal_lahir') }}" class="w-full border border-stone-200 rounded-xl px-4 py-2.5 mt-1.5 text-[14px] focus:outline-none focus:border-emerald-700 focus:ring-1 focus:ring-emerald-700 transition"></div>
    </div>
    <div><label class="font-bold text-sm">Alamat Lengkap *</label><textarea name="alamat" required class="w-full border border-stone-200 rounded-xl px-4 py-2.5 mt-1.5 text-[14px] focus:outline-none focus:border-emerald-700 focus:ring-1 focus:ring-emerald-700 transition" rows="2">{{ old('alamat') }}</textarea></div>

    <h2 class="font-extrabold text-emerald-950 text-[16px] pt-2">Data Orang Tua / Wali</h2>
    <div class="grid md:grid-cols-2 gap-4">
      <div><label class="font-bold text-sm">Nama Ayah/Wali *</label><input name="nama_ayah" required value="{{ old('nama_ayah') }}" class="w-full border border-stone-200 rounded-xl px-4 py-2.5 mt-1.5 text-[14px] focus:outline-none focus:border-emerald-700 focus:ring-1 focus:ring-emerald-700 transition"></div>
      <div><label class="font-bold text-sm">Pekerjaan Ayah *</label><input name="pekerjaan_ayah" required value="{{ old('pekerjaan_ayah') }}" class="w-full border border-stone-200 rounded-xl px-4 py-2.5 mt-1.5 text-[14px] focus:outline-none focus:border-emerald-700 focus:ring-1 focus:ring-emerald-700 transition"></div>
    </div>
    <div class="grid md:grid-cols-2 gap-4">
      <div><label class="font-bold text-sm">Nama Ibu *</label><input name="nama_ibu" required value="{{ old('nama_ibu') }}" class="w-full border border-stone-200 rounded-xl px-4 py-2.5 mt-1.5 text-[14px] focus:outline-none focus:border-emerald-700 focus:ring-1 focus:ring-emerald-700 transition"></div>
      <div><label class="font-bold text-sm">Pekerjaan Ibu *</label><input name="pekerjaan_ibu" required value="{{ old('pekerjaan_ibu') }}" class="w-full border border-stone-200 rounded-xl px-4 py-2.5 mt-1.5 text-[14px] focus:outline-none focus:border-emerald-700 focus:ring-1 focus:ring-emerald-700 transition"></div>
    </div>
    <div><label class="font-bold text-sm">Nomor yang Bisa Dihubungi *</label><input name="no_hp" required value="{{ old('no_hp') }}" class="w-full border border-stone-200 rounded-xl px-4 py-2.5 mt-1.5 text-[14px] focus:outline-none focus:border-emerald-700 focus:ring-1 focus:ring-emerald-700 transition" placeholder="08... (WA aktif)"></div>

    <h2 class="font-extrabold text-emerald-950 text-[16px] pt-2">Pendidikan</h2>
    <div class="grid md:grid-cols-2 gap-4">
      <div><label class="font-bold text-sm">Jenjang yang Ingin Ditempuh *</label><select name="jenjang" required class="w-full border border-stone-200 rounded-xl px-4 py-2.5 mt-1.5 text-[14px] focus:outline-none focus:border-emerald-700 focus:ring-1 focus:ring-emerald-700 transition bg-white"><option>SMP KUNUUZUL IMAM KAUMAN</option><option>MA KUNUUZUL IMAM KAUMAN</option><option>KULIAH</option><option>LAINNYA</option></select></div>
      <div><label class="font-bold text-sm">Nama Sekolah Asal *</label><input name="asal_sekolah" required value="{{ old('asal_sekolah') }}" class="w-full border border-stone-200 rounded-xl px-4 py-2.5 mt-1.5 text-[14px] focus:outline-none focus:border-emerald-700 focus:ring-1 focus:ring-emerald-700 transition" placeholder="SD/MI ..."></div>
    </div>

    <h2 class="font-extrabold text-emerald-950 text-[16px] pt-2">Berkas & Pengisian</h2>
    <div><label class="font-bold text-sm">Tanggal Pengisian Formulir *</label><input type="date" name="tanggal_pengisian" required value="{{ old('tanggal_pengisian', date('Y-m-d')) }}" class="w-full border border-stone-200 rounded-xl px-4 py-2.5 mt-1.5 text-[14px] focus:outline-none focus:border-emerald-700 focus:ring-1 focus:ring-emerald-700 transition"></div>
    <div><label class="font-bold text-sm">Ijazah atau Surat Keterangan Lulus</label><input type="file" name="file_ijazah" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" class="w-full border border-stone-200 rounded-xl px-4 py-2.5 mt-1.5 text-[14px] bg-white file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-emerald-950 file:text-white file:text-[12px] file:font-bold"><p class="text-[12px] text-stone-400 mt-1">PDF, DOC, atau gambar. Maks 4 MB.</p></div>
    <div><label class="font-bold text-sm">Kartu Keluarga *</label><input type="file" name="file_kk" required accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" class="w-full border border-stone-200 rounded-xl px-4 py-2.5 mt-1.5 text-[14px] bg-white file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-emerald-950 file:text-white file:text-[12px] file:font-bold"><p class="text-[12px] text-stone-400 mt-1">PDF, DOC, atau gambar. Maks 4 MB.</p></div>
    <div><label class="font-bold text-sm">KIP (Jika Ada)</label><input type="file" name="file_kip" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" class="w-full border border-stone-200 rounded-xl px-4 py-2.5 mt-1.5 text-[14px] bg-white file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-emerald-950 file:text-white file:text-[12px] file:font-bold"><p class="text-[12px] text-stone-400 mt-1">PDF, DOC, atau gambar. Maks 4 MB.</p></div>
    @include('partials.turnstile')
    <button class="w-full bg-emerald-950 hover:bg-emerald-800 text-white font-extrabold text-[13px] tracking-widest py-3.5 rounded-xl transition">KIRIM PENDAFTARAN →</button>
  </form>
  <p class="text-center text-sm text-stone-500 mt-4">Sudah mendaftar? <a href="{{ route('ppdb.cek') }}" class="font-bold text-emerald-800 hover:underline">Cek status pendaftaran →</a></p>
</div>
</div>
@endsection
