@extends('admin.layout')

@section('title', isset($santri) && $santri->exists ? 'Edit Santri' : 'Tambah Santri')
@section('crumb', 'Data identitas induk santri')

@section('content')
@php
  $sectionStyle = 'grid-column:1/-1;border-bottom:1px solid var(--line);padding-bottom:6px;margin-top:6px;font-weight:800;font-size:11px;letter-spacing:.08em;color:var(--muted)';
@endphp
<div class="card">
  <form method="POST" action="{{ $santri->exists ? route('admin.siakad.santri.update', $santri) : route('admin.siakad.santri.store') }}">
    @csrf
    @if($santri->exists)
      @method('PUT')
    @endif

    @if($errors->any())
      <div style="background:#fef2f2;border:1px solid #fecaca;color:#b91c1c;border-radius:10px;padding:10px 14px;margin-bottom:12px;font-size:13px">
        {{ $errors->first() }}
      </div>
    @endif

    <div class="form-grid">
      <div style="{{ $sectionStyle }}">DATA DIRI</div>

      <label class="f">Nama lengkap*
        <input name="nama" required value="{{ old('nama', $santri->nama ?? '') }}" placeholder="Sesuai akta / ijazah">
      </label>

      <label class="f">NIK
        <input name="nik" inputmode="numeric" maxlength="16" value="{{ old('nik', $santri->nik ?? '') }}" placeholder="16 digit (opsional)">
        <small style="color:var(--muted)">Dipakai untuk menghubungkan data PSB, alumni, dan surat.</small>
      </label>

      <label class="f">NIS
        <input name="nis" value="{{ old('nis', $santri->nis ?? '') }}" placeholder="Nomor induk santri (opsional)">
      </label>

      <label class="f">Jenis kelamin
        <select name="jenis_kelamin">
          <option value="">Belum diisi</option>
          <option value="L" @selected(old('jenis_kelamin', $santri->jenis_kelamin ?? '') === 'L')>Laki-laki</option>
          <option value="P" @selected(old('jenis_kelamin', $santri->jenis_kelamin ?? '') === 'P')>Perempuan</option>
        </select>
      </label>

      <label class="f">Tempat lahir
        <input name="tempat_lahir" value="{{ old('tempat_lahir', $santri->tempat_lahir ?? '') }}" placeholder="Contoh: Bondowoso">
      </label>

      <label class="f">Tanggal lahir
        <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $santri->tanggal_lahir?->format('Y-m-d')) }}">
      </label>

      <label class="f">Asal sekolah
        <input name="asal_sekolah" value="{{ old('asal_sekolah', $santri->asal_sekolah ?? '') }}" placeholder="SD/MI, SMP/MTs, SMA/MA...">
      </label>

      <div style="{{ $sectionStyle }}">KONTAK & WALI</div>

      <label class="f">No. HP / WhatsApp santri
        <input name="no_hp" value="{{ old('no_hp', $santri->no_hp ?? '') }}" placeholder="0812...">
      </label>

      <label class="f">Email
        <input type="email" name="email" value="{{ old('email', $santri->email ?? '') }}" placeholder="opsional">
      </label>

      <label class="f">Nama wali
        <input name="nama_wali" value="{{ old('nama_wali', $santri->nama_wali ?? '') }}">
      </label>

      <label class="f">No. HP wali
        <input name="hp_wali" value="{{ old('hp_wali', $santri->hp_wali ?? '') }}" placeholder="0812...">
      </label>

      <label class="f" style="grid-column:1/-1">Alamat
        <textarea name="alamat" style="min-height:90px" placeholder="Alamat lengkap">{{ old('alamat', $santri->alamat ?? '') }}</textarea>
      </label>

      <div style="{{ $sectionStyle }}">STATUS AKADEMIK</div>

      <label class="f">Status santri*
        <select name="status" required>
          @foreach(\App\Models\Santri::statusOptions() as $value => $label)
            <option value="{{ $value }}" @selected(old('status', $santri->status ?? 'aktif') === $value)>{{ $label }}</option>
          @endforeach
        </select>
      </label>

      <div></div>

      <label class="f">Tanggal masuk
        <input type="date" name="tanggal_masuk" value="{{ old('tanggal_masuk', $santri->tanggal_masuk?->format('Y-m-d')) }}">
      </label>

      <label class="f">Tanggal lulus
        <input type="date" name="tanggal_lulus" value="{{ old('tanggal_lulus', $santri->tanggal_lulus?->format('Y-m-d')) }}">
        <small style="color:var(--muted)">Isi jika santri sudah lulus.</small>
      </label>
    </div>

    <div style="display:flex;gap:8px;margin-top:16px;flex-wrap:wrap">
      <button type="submit" class="btn"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
      <a href="{{ route('admin.siakad.santri.index') }}" class="btn ghost">Batal</a>
    </div>
  </form>
</div>
@endsection
