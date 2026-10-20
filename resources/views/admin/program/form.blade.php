@extends('admin.layout')
@section('title',isset($program)?'Edit Program':'Tambah Program')
@section('content')
<div class="card"><form method="POST" action="{{ isset($program)?route('admin.program.update',$program):route('admin.program.store') }}" enctype="multipart/form-data">@csrf @if(isset($program))@method('PUT')@endif
<div class="form-grid">
<label class="f">Nama program*<input name="nama" required value="{{ old('nama',$program->nama??'') }}"></label>
<label class="f">Kategori*<select name="kategori">@foreach(['unggulan'=>'Unggulan','diniyah'=>'Diniyah','taklimi'=>'Taklimi','tathbiqi'=>'Tathbiqi','formal'=>'Formal','bahasa'=>'Bahasa','tahsin'=>'Tahsin','skill'=>'Skill','ekstrakurikuler'=>'Ekstrakurikuler'] as $k=>$l)<option value="{{ $k }}" @selected(old('kategori',$program->kategori??'')===$k)>{{ $l }}</option>@endforeach</select></label>
<label class="f" style="grid-column:1/-1">Deskripsi singkat<textarea name="deskripsi_singkat" style="min-height:70px">{{ old('deskripsi_singkat',$program->deskripsi_singkat??'') }}</textarea></label>
<label class="f" style="grid-column:1/-1">Deskripsi lengkap<textarea name="deskripsi">{{ old('deskripsi',$program->deskripsi??'') }}</textarea></label>
<label class="f">Ikon (emoji)<input name="ikon" value="{{ old('ikon',$program->ikon??'📖') }}"></label>
<label class="f">Urutan<input type="number" name="urutan" value="{{ old('urutan',$program->urutan??0) }}"></label>
<label class="f">Gambar<input type="file" name="gambar" accept="image/*"></label>
</div>
<label class="check" style="margin:6px 0 14px"><input type="checkbox" name="is_aktif" value="1" @checked(old('is_aktif',$program->is_aktif??true))> Tampilkan di website</label>
<div style="display:flex;gap:8px"><button class="btn">Simpan</button><a class="btn ghost" href="{{ route('admin.program.index') }}">Batal</a></div></form></div>
@endsection
