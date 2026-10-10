@extends('admin.layout')
@section('title','Organisasi')
@section('content')
<div class="card"><table><tr><th>Urutan</th><th>Organisasi</th><th>Kategori</th><th>Aktif</th><th>Aksi</th></tr>
@foreach($organisasis as $o)<tr><td>{{ $o->urutan }}</td><td><b>{{ $o->singkatan }}</b> — {{ $o->nama }}<br><small style="color:var(--muted)">{{ \Str::limit($o->deskripsi_singkat ?? $o->deskripsi,90) }}</small></td><td>{!! $o->kategori==='dalam'?'<span class="pill p-green">dalam</span>':'<span class="pill p-blue">luar</span>' !!}</td><td>{!! $o->is_aktif?'<span class="pill p-green">aktif</span>':'<span class="pill p-gray">off</span>' !!}</td><td><div class="actions"><a class="icon-btn" href="{{ route('admin.organisasi.edit',$o) }}"><i class="fa-solid fa-pen"></i></a><form method="POST" action="{{ route('admin.organisasi.destroy',$o) }}" onsubmit="return confirm('Hapus?')">@csrf @method('DELETE')<button class="icon-btn danger"><i class="fa-solid fa-trash"></i></button></form></div></td></tr>@endforeach
</table></div>
@endsection
