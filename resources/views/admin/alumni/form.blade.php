@extends('admin.layout')

@section('title', isset($alumni) ? 'Edit Alumni' : 'Tambah Alumni')
@section('crumb', 'Data direktori alumni')

@section('content')
@php
  $sectionStyle = 'grid-column:1/-1;border-bottom:1px solid var(--line);padding-bottom:6px;margin-top:6px;font-weight:800;font-size:11px;letter-spacing:.08em;color:var(--muted)';
@endphp
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
      <div style="{{ $sectionStyle }}">DATA DIRI</div>

      <label class="f">Nama*
        <input name="nama" required value="{{ old('nama', $alumni->nama ?? '') }}">
      </label>

      <label class="f">NIK
        <input name="nik" inputmode="numeric" maxlength="16" value="{{ old('nik', $alumni->nik ?? '') }}" placeholder="16 digit (opsional)">
        <small style="color:var(--muted)">Dipakai alumni untuk cek data mandiri di halaman pendaftaran.</small>
      </label>

      <label class="f">Jenis kelamin
        <select name="jenis_kelamin">
          <option value="">Belum diisi</option>
          @foreach(\App\Models\Alumni::jenisKelaminOptions() as $value => $label)
            <option value="{{ $value }}" @selected(old('jenis_kelamin', $alumni->jenis_kelamin ?? '') === $value)>{{ $label }}</option>
          @endforeach
        </select>
      </label>

      <label class="f">Status hidup
        <select name="status_hidup">
          @foreach(\App\Models\Alumni::statusHidupOptions() as $value => $label)
            <option value="{{ $value }}" @selected(old('status_hidup', $alumni->status_hidup ?? 'hidup') === $value)>{{ $label }}</option>
          @endforeach
        </select>
      </label>

      <label class="f">Tempat lahir
        <input name="tempat_lahir" value="{{ old('tempat_lahir', $alumni->tempat_lahir ?? '') }}" placeholder="Contoh: Bondowoso">
      </label>

      <label class="f">Tanggal lahir
        <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', isset($alumni) && $alumni->tanggal_lahir ? $alumni->tanggal_lahir->format('Y-m-d') : '') }}">
      </label>

      <label class="f">Angkatan (tahun masuk)
        <input type="number" name="angkatan" min="1900" max="2100" value="{{ old('angkatan', $alumni->angkatan ?? '') }}">
      </label>

      <label class="f">Tahun lulus
        <input type="number" name="tahun_lulus" min="1900" max="2100" value="{{ old('tahun_lulus', $alumni->tahun_lulus ?? '') }}">
      </label>

      <div style="{{ $sectionStyle }}">ALAMAT LENGKAP</div>

      <label class="f">Provinsi
        <input name="provinsi" value="{{ old('provinsi', $alumni->provinsi ?? '') }}" placeholder="Contoh: Jawa Timur">
      </label>

      <label class="f">Kabupaten
        <input name="kabupaten" list="admin-kabupaten-options" value="{{ old('kabupaten', $alumni->kabupaten ?? '') }}" placeholder="Contoh: Bondowoso / Jember">
        <datalist id="admin-kabupaten-options">
          @foreach(\App\Support\WilayahPk::kabupatenOptions() as $option)
            <option value="{{ $option }}"></option>
          @endforeach
        </datalist>
      </label>

      <label class="f">Kecamatan
        <input name="kecamatan" list="admin-kecamatan-options" value="{{ old('kecamatan', $alumni->kecamatan ?? '') }}" placeholder="Contoh: Binakal">
        <datalist id="admin-kecamatan-options">
          @foreach(\App\Support\WilayahPk::kecamatanOptions() as $option)
            <option value="{{ $option }}"></option>
          @endforeach
        </datalist>
      </label>

      <label class="f">Desa / kelurahan
        <input name="desa" value="{{ old('desa', $alumni->desa ?? '') }}">
      </label>

      <label class="f">Dusun
        <input name="dusun" value="{{ old('dusun', $alumni->dusun ?? '') }}">
      </label>

      <label class="f">RT
        <input name="rt" inputmode="numeric" maxlength="3" value="{{ old('rt', $alumni->rt ?? '') }}" placeholder="Contoh: 001">
      </label>

      <label class="f">RW
        <input name="rw" inputmode="numeric" maxlength="3" value="{{ old('rw', $alumni->rw ?? '') }}" placeholder="Contoh: 002">
      </label>

      <label class="f">Wilayah PK
        <select name="wilayah_pk">
          <option value="">Otomatis dari kabupaten/kecamatan</option>
          @foreach(\App\Support\WilayahPk::pkOptions() as $pk)
            <option value="{{ $pk }}" @selected(old('wilayah_pk', $alumni->wilayah_pk ?? '') === $pk)>{{ $pk }}</option>
          @endforeach
        </select>
        <small style="color:var(--muted)">Kosongkan agar sistem menentukan otomatis.</small>
      </label>

      <div style="{{ $sectionStyle }}">KONTAK & DATA LAINNYA</div>

      <label class="f">Pekerjaan
        <input name="pekerjaan" value="{{ old('pekerjaan', $alumni->pekerjaan ?? '') }}">
      </label>

      <label class="f">No. HP / WhatsApp
        <input name="no_hp" value="{{ old('no_hp', $alumni->no_hp ?? '') }}" placeholder="0812...">
      </label>

      <label class="f">Lembaga (data lama)
        <input name="lembaga" value="{{ old('lembaga', $alumni->lembaga ?? '') }}" placeholder="Mis. MTs, MA, Tahfidz">
      </label>

      <label class="f">Domisili (data lama)
        <input name="domisili" value="{{ old('domisili', $alumni->domisili ?? '') }}" placeholder="Kota / kabupaten">
      </label>

      <label class="f">Email (data lama)
        <input type="email" name="email" value="{{ old('email', $alumni->email ?? '') }}">
      </label>

      <label class="f" style="grid-column:1/-1">Tentang / catatan
        <textarea name="tentang" style="min-height:110px">{{ old('tentang', $alumni->tentang ?? '') }}</textarea>
      </label>

      <div style="{{ $sectionStyle }}">VERIFIKASI</div>

      <label class="f">Status
        <select name="status">
          @foreach(\App\Models\Alumni::statusOptions() as $value => $label)
            <option value="{{ $value }}" @selected(old('status', $alumni->status ?? \App\Models\Alumni::STATUS_APPROVED) === $value)>{{ $label }}</option>
          @endforeach
        </select>
      </label>

      <label class="f">Catatan admin
        <input name="catatan_admin" value="{{ old('catatan_admin', $alumni->catatan_admin ?? '') }}" placeholder="Alasan ditolak / catatan verifikasi">
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
      Tampilkan di direktori publik (hanya jika status Disetujui)
    </label>

    <div style="display:flex;gap:8px">
      <button class="btn">Simpan</button>
      <a class="btn ghost" href="{{ route('admin.alumni.index') }}">Batal</a>
    </div>
  </form>
</div>
@endsection
