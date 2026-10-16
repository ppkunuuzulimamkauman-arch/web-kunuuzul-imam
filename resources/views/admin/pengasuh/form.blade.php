@extends('admin.layout')
@section('title',isset($pengasuh)?'Edit Pengasuh':'Tambah Pengasuh')
@section('content')
<div class="card"><form method="POST" action="{{ isset($pengasuh)?route('admin.pengasuh.update',$pengasuh):route('admin.pengasuh.store') }}" enctype="multipart/form-data">@csrf @if(isset($pengasuh))@method('PUT')@endif
@if($errors->any())<div style="background:#fef2f2;border:1px solid #fecaca;color:#b91c1c;border-radius:10px;padding:10px 14px;margin-bottom:12px;font-size:13px">{{ $errors->first() }}</div>@endif
<div class="form-grid">
<label class="f">Nama*<input name="nama" required value="{{ old('nama',$pengasuh->nama??'') }}"></label>
<label class="f">Jabatan<input name="jabatan" value="{{ old('jabatan',$pengasuh->jabatan??'') }}" placeholder="Pengasuh — Pengampu Tafsir"></label>
<label class="f" style="grid-column:1/-1">Bio<textarea name="bio" style="min-height:90px">{{ old('bio',$pengasuh->bio??'') }}</textarea></label>
<label class="f">Foto (maks 2MB)<input type="file" name="foto" accept="image/*">@if(!empty($pengasuh->foto))<small style="color:var(--muted)">Ada: {{ $pengasuh->foto }}</small><br><img src="{{ '/storage/'.$pengasuh->foto }}" style="width:80px;height:80px;object-fit:cover;border-radius:50%;margin-top:6px" onerror="this.style.display='none'">@endif</label>
<label class="f">Urutan<input type="number" name="urutan" value="{{ old('urutan',$pengasuh->urutan??0) }}"></label>
</div>
<label class="check" style="margin:6px 0 14px"><input type="checkbox" name="is_aktif" value="1" @checked(old('is_aktif',$pengasuh->is_aktif??true))> Aktif</label>
<div style="display:flex;gap:8px"><button class="btn">Simpan</button><a class="btn ghost" href="{{ route('admin.pengasuh.index') }}">Batal</a></div></form></div>
@endsection
