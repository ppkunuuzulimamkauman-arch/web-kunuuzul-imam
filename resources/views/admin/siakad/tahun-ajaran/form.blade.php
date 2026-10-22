@extends('admin.layout')

@section('title', $tahunAjaran->exists ? 'Edit Tahun Ajaran' : 'Tambah Tahun Ajaran')
@section('crumb', 'Periode akademik')

@section('content')
<div class="card" style="max-width:680px">
  <form method="POST" action="{{ $tahunAjaran->exists ? route('admin.siakad.tahun-ajaran.update', $tahunAjaran) : route('admin.siakad.tahun-ajaran.store') }}">
    @csrf
    @if($tahunAjaran->exists)
      @method('PUT')
    @endif

    @if($errors->any())
      <div style="background:#fef2f2;border:1px solid #fecaca;color:#b91c1c;border-radius:10px;padding:10px 14px;margin-bottom:12px;font-size:13px">
        {{ $errors->first() }}
      </div>
    @endif

    <div class="form-grid">
      <label class="f" style="grid-column:1/-1">Nama tahun ajaran*
        <input name="nama" required value="{{ old('nama', $tahunAjaran->nama ?? '') }}" placeholder="Contoh: 2026/2027">
      </label>

      <label class="f">Tanggal mulai
        <input type="date" name="mulai" value="{{ old('mulai', $tahunAjaran->mulai?->format('Y-m-d')) }}">
      </label>

      <label class="f">Tanggal selesai
        <input type="date" name="selesai" value="{{ old('selesai', $tahunAjaran->selesai?->format('Y-m-d')) }}">
      </label>

      <label class="f" style="grid-column:1/-1;display:flex;align-items:center;gap:10px;margin-top:4px">
        <input type="hidden" name="is_aktif" value="0">
        <input type="checkbox" name="is_aktif" value="1" style="width:auto;margin:0" @checked(old('is_aktif', $tahunAjaran->is_aktif ?? false))>
        Jadikan tahun ajaran aktif
      </label>
      <small style="grid-column:1/-1;color:var(--muted);margin-top:-8px">Hanya satu tahun ajaran yang bisa aktif. Mengaktifkan tahun ini akan menonaktifkan tahun lainnya.</small>
    </div>

    <div style="display:flex;gap:8px;margin-top:16px;flex-wrap:wrap">
      <button type="submit" class="btn"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
      <a href="{{ route('admin.siakad.tahun-ajaran.index') }}" class="btn ghost">Batal</a>
    </div>
  </form>
</div>
@endsection
