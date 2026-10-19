@extends('admin.layout')

@section('title', isset($alumni) ? 'Edit Alumni' : 'Tambah Alumni')
@section('crumb', 'Data direktori alumni')

@section('content')
<div class="card">
  <form method="POST"
        action="{{ isset($alumni) ? route('admin.alumni.update', $alumni) : route('admin.alumni.store') }}"
        enctype="multipart/form-data">
    @csrf
    @if(isset($alumni))
      @method('PUT')
    @endif

    @if($errors->any())
      <div style="background:#fef2f2;border:1px solid #fecaca;color:#b91c1c;border-radius:10px;padding:10px 14px;margin-bottom:12px;font-size:13px">
        {{ $errors->first() }}
      </div>
    @endif

    <div class="form-grid">
      <label class="f">Nama*
        <input name="nama" required value="{{ old('nama', $alumni->nama ?? '') }}">
      </label>

      <label class="f">Angkatan (tahun masuk)
        <input type="number" name="angkatan" min="1900" max="2100" value="{{ old('angkatan', $alumni->angkatan ?? '') }}">
      </label>

      <label class="f">Tahun lulus
        <input type="number" name="tahun_lulus" min="1900" max="2100" value="{{ old('tahun_lulus', $alumni->tahun_lulus ?? '') }}">
      </label>

      <label class="f">Lembaga
        <input name="lembaga" value="{{ old('lembaga', $alumni->lembaga ?? '') }}" placeholder="Mis. MTs, MA, Tahfidz">
      </label>

      <label class="f">Domisili
        <input name="domisili" value="{{ old('domisili', $alumni->domisili ?? '') }}" placeholder="Kota / kabupaten">
      </label>

      <label class="f">Pekerjaan
        <input name="pekerjaan" value="{{ old('pekerjaan', $alumni->pekerjaan ?? '') }}">
      </label>

      <label class="f">No. HP / WhatsApp
        <input name="no_hp" value="{{ old('no_hp', $alumni->no_hp ?? '') }}" placeholder="0812...">
      </label>

      <label class="f">Email
        <input type="email" name="email" value="{{ old('email', $alumni->email ?? '') }}">
      </label>

      <label class="f" style="grid-column:1/-1">Tentang / catatan
        <textarea name="tentang" style="min-height:110px">{{ old('tentang', $alumni->tentang ?? '') }}</textarea>
      </label>

      <label class="f">Foto (maks 2MB)
        <input type="file" name="foto" accept="image/*">
        @if(isset($alumni) && $alumni->fotoUrl())
          <small style="color:var(--muted)">Foto saat ini:</small><br>
          <img src="{{ $alumni->fotoUrl() }}" alt="{{ $alumni->nama }}"
               style="width:90px;height:90px;object-fit:cover;border-radius:14px;margin-top:6px" onerror="this.style.display='none'">
        @endif
      </label>
    </div>

    <label class="check" style="margin:6px 0 14px">
      <input type="checkbox" name="is_tampil" value="1" @checked(old('is_tampil', $alumni->is_tampil ?? true))>
      Tampilkan di direktori publik
    </label>

    <div style="display:flex;gap:8px">
      <button class="btn">Simpan</button>
      <a class="btn ghost" href="{{ route('admin.alumni.index') }}">Batal</a>
    </div>
  </form>
</div>
@endsection
