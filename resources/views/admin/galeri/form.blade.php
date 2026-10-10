@extends('admin.layout')
@section('title',isset($galeri)?'Edit Galeri':'Upload Galeri')
@section('content')
<div class="card"><form method="POST" action="{{ isset($galeri)?route('admin.galeri.update',$galeri):route('admin.galeri.store') }}" enctype="multipart/form-data">@csrf @if(isset($galeri))@method('PUT')@endif
<div class="form-grid">
<label class="f">Judul*<input name="judul" required value="{{ old('judul',$galeri->judul??'') }}"></label>
<label class="f">Kategori<select name="kategori">@foreach(['kegiatan'=>'Kegiatan','asrama'=>'Asrama','pembelajaran'=>'Pembelajaran','acara'=>'Acara','fasilitas'=>'Fasilitas'] as $k=>$l)<option value="{{ $k }}" @selected(old('kategori',$galeri->kategori??'kegiatan')===$k)>{{ $l }}</option>@endforeach</select></label>
<label class="f" style="grid-column:1/-1">Keterangan<textarea name="keterangan" style="min-height:70px">{{ old('keterangan',$galeri->keterangan??'') }}</textarea></label>
<label class="f">Foto<input type="file" name="gambar" accept="image/*"></label>
</div>
<div style="display:flex;gap:8px;margin-top:12px"><button class="btn">Simpan</button><a class="btn ghost" href="{{ route('admin.galeri.index') }}">Batal</a></div></form></div>
@endsection
