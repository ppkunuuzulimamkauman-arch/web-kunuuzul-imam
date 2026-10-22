@extends('admin.layout')

@section('title', $kelas->exists ? 'Edit Kelas' : 'Tambah Kelas')
@section('crumb', 'Kelas pada tahun ajaran')

@section('content')
<div class="card" style="max-width:720px">
  @if($tahunAjarans->isEmpty())
    <div style="background:#fef3c7;border:1px solid #fde68a;color:#92400e;border-radius:10px;padding:10px 14px;margin-bottom:12px;font-size:13px">
      Belum ada tahun ajaran. <a href="{{ route('admin.siakad.tahun-ajaran.create') }}" style="color:inherit;font-weight:800">Buat tahun ajaran dulu</a>.
    </div>
  @endif

  <form method="POST" action="{{ $kelas->exists ? route('admin.siakad.kelas.update', $kelas) : route('admin.siakad.kelas.store') }}">
    @csrf
    @if($kelas->exists)
      @method('PUT')
    @endif

    @if($errors->any())
      <div style="background:#fef2f2;border:1px solid #fecaca;color:#b91c1c;border-radius:10px;padding:10px 14px;margin-bottom:12px;font-size:13px">
        {{ $errors->first() }}
      </div>
    @endif

    <div class="form-grid">
      <label class="f" style="grid-column:1/-1">Tahun ajaran*
        <select name="tahun_ajaran_id" required>
          <option value="">Pilih tahun ajaran</option>
          @foreach($tahunAjarans as $tahun)
            <option value="{{ $tahun->id }}" @selected((string) old('tahun_ajaran_id', $kelas->tahun_ajaran_id ?? '') === (string) $tahun->id)>{{ $tahun->nama }}{{ $tahun->is_aktif ? ' (aktif)' : '' }}</option>
          @endforeach
        </select>
      </label>

      <label class="f">Nama kelas*
        <input name="nama" required value="{{ old('nama', $kelas->nama ?? '') }}" placeholder="Contoh: 7A / Ula 1">
      </label>

      <label class="f">Jenjang
        <input name="jenjang" value="{{ old('jenjang', $kelas->jenjang ?? '') }}" placeholder="Contoh: MTs / MA / Tahfidz">
      </label>

      <label class="f">Tingkat
        <input type="number" name="tingkat" min="1" max="12" value="{{ old('tingkat', $kelas->tingkat ?? '') }}" placeholder="Contoh: 7">
      </label>

      <label class="f">Wali kelas
        <input name="wali_kelas" value="{{ old('wali_kelas', $kelas->wali_kelas ?? '') }}">
      </label>

      <label class="f" style="grid-column:1/-1;display:flex;align-items:center;gap:10px;margin-top:4px">
        <input type="hidden" name="is_aktif" value="0">
        <input type="checkbox" name="is_aktif" value="1" style="width:auto;margin:0" @checked(old('is_aktif', $kelas->is_aktif ?? true))>
        Kelas aktif
      </label>
    </div>

    <div style="display:flex;gap:8px;margin-top:16px;flex-wrap:wrap">
      <button type="submit" class="btn" @disabled($tahunAjarans->isEmpty())><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
      <a href="{{ route('admin.siakad.kelas.index') }}" class="btn ghost">Batal</a>
    </div>
  </form>
</div>
@endsection
