@extends('admin.layout')
@section('title',isset($agenda)?'Edit Agenda':'Tambah Agenda')
@section('content')
<div class="card" style="max-width:640px"><form method="POST" action="{{ isset($agenda)?route('admin.agenda.update',$agenda):route('admin.agenda.store') }}">@csrf @if(isset($agenda))@method('PUT')@endif
<label class="f">Nama acara*<input name="judul" required value="{{ old('judul',$agenda->judul??'') }}" placeholder="cth: Sorogan Fathul Qorib"></label>
<div class="form-grid">
<label class="f">Tanggal*<input type="date" name="tanggal" required value="{{ old('tanggal',isset($agenda->tanggal)?$agenda->tanggal->format('Y-m-d'):'') }}"></label>
<label class="f">Tempat<input name="tempat" value="{{ old('tempat',$agenda->tempat??'') }}" placeholder="cth: Aula / Lapangan"></label>
</div>
<label class="f">Keterangan<textarea name="deskripsi" style="min-height:70px">{{ old('deskripsi',$agenda->deskripsi??'') }}</textarea></label>
<label class="check" style="margin:4px 0 14px"><input type="checkbox" name="is_aktif" value="1" @checked(old('is_aktif',$agenda->is_aktif??true))> Tampilkan di landing</label>
<div style="display:flex;gap:8px"><button class="btn">Simpan</button><a class="btn ghost" href="{{ route('admin.agenda.index') }}">Batal</a></div></form></div>
@endsection
