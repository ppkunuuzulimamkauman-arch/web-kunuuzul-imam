@extends('admin.layout')

@section('title', 'Import Data Alumni')
@section('crumb', 'Tambah data alumni massal dari file CSV')

@section('content')
@php $importErrors = session('import_errors', []); @endphp
@if(is_array($importErrors) && count($importErrors))
  <div class="card" style="border-color:#fecaca;background:#fef2f2;margin-bottom:14px">
    <b style="color:#991b1b">Sebagian baris dilewati:</b>
    <ul style="margin:8px 0 0 18px;color:#991b1b;font-size:13px;line-height:1.7">
      @foreach($importErrors as $err)
        <li>{{ $err }}</li>
      @endforeach
    </ul>
  </div>
@endif

<div class="card">
  <p style="font-size:14px;color:var(--muted);line-height:1.7;margin:0 0 10px">
    Unggah file CSV dengan baris judul (header). Kolom wajib: <b>Nama</b>.
    Kolom lain opsional: NIK, Angkatan, Tahun Lulus, Lembaga, Domisili, Pekerjaan,
    No. HP, Email, Status, Tampil, Tentang, Catatan Admin.
  </p>

  <ul style="font-size:13px;color:var(--muted);line-height:1.8;margin:0 0 16px 18px">
    <li>NIK harus 16 digit dan valid. NIK yang sudah terdaftar akan dilewati.</li>
    <li>Status: <b>Menunggu</b>, <b>Disetujui</b>, atau <b>Ditolak</b>. Kosong = Disetujui.</li>
    <li>Tampil: <b>ya</b> / <b>tidak</b>. Kosong = ya (jika status Disetujui).</li>
    <li>Nama + angkatan yang sama akan dilewati (anti-duplikat).</li>
    <li>Maksimal 2MB dan 2000 baris per file. Foto tidak termasuk dalam CSV.</li>
  </ul>

  <div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:18px">
    <a class="btn ghost" href="{{ route('admin.alumni.import.template') }}">
      <i class="fa-solid fa-download"></i> Download Template
    </a>
    <a class="btn ghost" href="{{ route('admin.alumni.index') }}">
      <i class="fa-solid fa-arrow-left"></i> Kembali
    </a>
  </div>

  <form method="POST" action="{{ route('admin.alumni.import.store') }}" enctype="multipart/form-data">
    @csrf
    <label class="f">File CSV
      <input type="file" name="file" accept=".csv,text/csv" required>
    </label>
    @error('file')
      <p style="color:#dc2626;font-size:13px;font-weight:700;margin-top:6px">{{ $message }}</p>
    @enderror

    <div style="margin-top:14px">
      <button class="btn"><i class="fa-solid fa-file-import"></i> Mulai Import</button>
    </div>
  </form>
</div>
@endsection
