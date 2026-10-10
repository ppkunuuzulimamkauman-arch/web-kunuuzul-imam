@extends('admin.layout')
@section('title',isset($lembaga)?'Edit Lembaga':'Tambah Lembaga')
@section('content')
<div class="card"><form method="POST" action="{{ isset($lembaga)?route('admin.lembaga.update',$lembaga):route('admin.lembaga.store') }}" enctype="multipart/form-data">@csrf @if(isset($lembaga))@method('PUT')@endif
<div class="form-grid">
<label class="f">Nama*<input name="nama" required value="{{ old('nama',$lembaga->nama??'') }}"></label>
<label class="f">Ikon (emoji)<input name="ikon" value="{{ old('ikon',$lembaga->ikon??'🕌') }}"></label>
<label class="f" style="grid-column:1/-1">Deskripsi<textarea name="deskripsi">{{ old('deskripsi',$lembaga->deskripsi??'') }}</textarea></label>
<label class="f">URL / Link<input name="url" value="{{ old('url',$lembaga->url??'') }}" placeholder="https://..."></label>
<label class="f">Urutan<input type="number" name="urutan" value="{{ old('urutan',$lembaga->urutan??0) }}"></label>
<label class="f">Gambar<input type="file" name="gambar" accept="image/*"></label>
</div>
<label class="check" style="margin:6px 0 14px"><input type="checkbox" name="is_aktif" value="1" @checked(old('is_aktif',$lembaga->is_aktif??true))> Aktif</label>
<div style="display:flex;gap:8px"><button class="btn">Simpan</button><a class="btn ghost" href="{{ route('admin.lembaga.index') }}">Batal</a></div></form></div>
@endsection
