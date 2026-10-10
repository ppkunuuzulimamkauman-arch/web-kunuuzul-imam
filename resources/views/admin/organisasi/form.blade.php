@extends('admin.layout')
@section('title',isset($organisasi)?'Edit Organisasi':'Tambah Organisasi')
@section('content')
<div class="card"><form method="POST" action="{{ isset($organisasi)?route('admin.organisasi.update',$organisasi):route('admin.organisasi.store') }}" enctype="multipart/form-data">@csrf @if(isset($organisasi))@method('PUT')@endif
<div class="form-grid">
<label class="f">Singkatan*<input name="singkatan" required value="{{ old('singkatan',$organisasi->singkatan??'') }}" placeholder="FOKUS"></label>
<label class="f">Nama Panjang<input name="nama" value="{{ old('nama',$organisasi->nama??'') }}" placeholder="Forum Komunikasi Santri"></label>
<label class="f">Kategori*<select name="kategori" required><option value="dalam" @selected(old('kategori',$organisasi->kategori??'dalam')==='dalam')>Dalam Pesantren</option><option value="luar" @selected(old('kategori',$organisasi->kategori??'')==='luar')>Luar (Naungan Pondok)</option></select></label>
<label class="f">Urutan<input type="number" name="urutan" value="{{ old('urutan',$organisasi->urutan??0) }}"></label>
<label class="f" style="grid-column:1/-1">Deskripsi Singkat<textarea name="deskripsi_singkat" rows="2">{{ old('deskripsi_singkat',$organisasi->deskripsi_singkat??'') }}</textarea></label>
<label class="f" style="grid-column:1/-1">Deskripsi Panjang<textarea name="deskripsi" rows="6">{{ old('deskripsi',$organisasi->deskripsi??'') }}</textarea></label>
<label class="f" style="grid-column:1/-1">Struktur / Susunan Pengurus<textarea name="struktur" rows="6">{{ old('struktur',$organisasi->struktur??'') }}</textarea><span class="set-hint" style="font-size:12px;color:var(--muted)">Satu baris satu jabatan. Format: Jabatan|Nama — contoh: Ketua|Ust. Ahmad</span></label>
<label class="f">Logo<input type="file" name="logo" accept="image/*"></label>
</div>
<label class="check" style="margin:6px 0 14px"><input type="checkbox" name="is_aktif" value="1" @checked(old('is_aktif',$organisasi->is_aktif??true))> Aktif</label>
<div style="display:flex;gap:8px"><button class="btn">Simpan</button><a class="btn ghost" href="{{ route('admin.organisasi.index') }}">Batal</a></div></form></div>
@endsection
