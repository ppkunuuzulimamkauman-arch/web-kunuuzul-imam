@extends('admin.layout')
@section('title',isset($slide)?'Edit Slide':'Tambah Slide')
@section('content')
<div class="card"><form method="POST" action="{{ isset($slide)?route('admin.hero.update',$slide):route('admin.hero.store') }}" enctype="multipart/form-data">@csrf @if(isset($slide))@method('PUT')@endif
<div class="form-grid">
<label class="f">Label<input name="label" value="{{ old('label',$slide->label??'') }}" placeholder="TAHFIDZ QURAN"></label>
<label class="f">Badge<input name="badge" value="{{ old('badge',$slide->badge??'') }}" placeholder="📖 Tahfidz • Sanad 30 Juz"></label>
<label class="f" style="grid-column:1/-1">Title (boleh pakai &lt;br&gt;)*<input name="title" required value="{{ old('title',$slide->title??'') }}"></label>
<label class="f" style="grid-column:1/-1">Sub<textarea name="sub" style="min-height:70px">{{ old('sub',$slide->sub??'') }}</textarea></label>
<label class="f">Desc kecil<input name="desc" value="{{ old('desc',$slide->desc??'') }}"></label>
<label class="f">Gambar background<input type="file" name="gambar" accept="image/*" onchange="if(this.files[0]){document.getElementById('prevHero').src=URL.createObjectURL(this.files[0]);document.getElementById('prevHero').style.display='block';}"><small style="color:var(--muted)">JPG/PNG/WEBP, maks 4MB, landscape ±1600×900</small></label>
<label class="f">CTA 1 teks<input name="cta1_text" value="{{ old('cta1_text',$slide->cta1_text??'') }}"></label>
<label class="f">CTA 1 URL<input name="cta1_url" value="{{ old('cta1_url',$slide->cta1_url??'/ppdb') }}"></label>
<label class="f">CTA 2 teks<input name="cta2_text" value="{{ old('cta2_text',$slide->cta2_text??'') }}"></label>
<label class="f">CTA 2 URL<input name="cta2_url" value="{{ old('cta2_url',$slide->cta2_url??'/profil') }}"></label>
<label class="f">Urutan<input type="number" name="urutan" value="{{ old('urutan',$slide->urutan??0) }}"></label>
</div>
<div style="margin:4px 0 14px"><div style="font-size:12.5px;font-weight:700;margin-bottom:6px">Pratinjau gambar</div><img id="prevHero" src="{{ isset($slide->gambar)?$slide->gambar:'' }}" style="max-width:100%;max-height:220px;border-radius:12px;border:1px solid var(--line);{{ isset($slide->gambar)?'':'display:none' }}" onerror="this.style.display='none'"></div>
<label class="check" style="margin:6px 0 14px"><input type="checkbox" name="is_aktif" value="1" @checked(old('is_aktif',$slide->is_aktif??true))> Aktif</label>
<div style="display:flex;gap:8px"><button class="btn">Simpan</button><a class="btn ghost" href="{{ route('admin.hero.index') }}">Batal</a></div></form></div>
@endsection
