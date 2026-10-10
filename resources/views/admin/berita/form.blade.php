@extends('admin.layout')
@section('title',isset($berita)?'Edit Berita':'Tulis Berita')
@section('crumb','Berita & artikel pondok')
@section('content')
<div class="card">
<form method="POST" action="{{ isset($berita)?route('admin.berita.update',$berita):route('admin.berita.store') }}" enctype="multipart/form-data">@csrf @if(isset($berita))@method('PUT')@endif
<div class="form-grid">
<label class="f" style="grid-column:1/-1">Judul*<input name="judul" required value="{{ old('judul',$berita->judul??'') }}" placeholder="cth: Wisuda Tahfidz Angkatan XIII"></label>
<label class="f">Kategori*<select name="kategori" required>@foreach(['berita'=>'Berita','artikel'=>'Artikel','acara'=>'Acara','ppdb'=>'PPDB','prestasi'=>'Prestasi','pengumuman'=>'Pengumuman','maklumat'=>'Maklumat Pondok','dawuh'=>'Dawuh Masyayikh','bahtsul'=>'Bahtsul Masail'] as $k=>$l)<option value="{{ $k }}" @selected(old('kategori',$berita->kategori??'berita')===$k)>{{ $l }}</option>@endforeach</select></label>
<label class="f">Penulis<input name="penulis" value="{{ old('penulis',$berita->penulis??auth()->user()->name) }}"></label>
<label class="f" style="grid-column:1/-1">Excerpt / Ringkasan<textarea name="excerpt" style="min-height:70px" placeholder="1-2 kalimat tampil di kartu berita">{{ old('excerpt',$berita->excerpt??'') }}</textarea></label>
<label class="f" style="grid-column:1/-1">Konten*<textarea name="konten" required placeholder="Isi lengkap berita...">{{ old('konten',$berita->konten??'') }}</textarea></label>
<label class="f">Gambar sampul<input type="file" name="gambar" accept="image/*">@if(!empty($berita->gambar))<small style="color:var(--muted)">Ada: {{ $berita->gambar }}</small>@endif</label>
<label class="f">Tanggal publish<input type="datetime-local" name="published_at" value="{{ old('published_at',isset($berita?->published_at)?$berita->published_at->format('Y-m-d\TH:i'):now()->format('Y-m-d\TH:i')) }}"></label>
</div>
<label class="check" style="margin:6px 0 14px"><input type="checkbox" name="is_published" value="1" @checked(old('is_published',$berita->is_published??true))> Publikasikan langsung (tampil di website)</label>
<div style="display:flex;gap:8px"><button class="btn"><i class="fa-solid fa-floppy-disk"></i> Simpan</button><a class="btn ghost" href="{{ route('admin.berita.index') }}">Batal</a></div>
</form>
</div>
@endsection
