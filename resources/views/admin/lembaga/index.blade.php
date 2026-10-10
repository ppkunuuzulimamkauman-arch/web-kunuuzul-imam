@extends('admin.layout')
@section('title','Lembaga')
@section('content')
<div class="card"><table><tr><th>Urutan</th><th>Nama</th><th>Aktif</th><th>Aksi</th></tr>
@foreach($lembagas as $l)<tr><td>{{ $l->urutan }}</td><td><b>{{ $l->ikon }} {{ $l->nama }}</b><br><small style="color:var(--muted)">{{ \Str::limit($l->deskripsi,90) }}</small></td><td>{!! $l->is_aktif?'<span class="pill p-green">aktif</span>':'<span class="pill p-gray">off</span>' !!}</td><td><div class="actions"><a class="icon-btn" href="{{ route('admin.lembaga.edit',$l) }}"><i class="fa-solid fa-pen"></i></a><form method="POST" action="{{ route('admin.lembaga.destroy',$l) }}" onsubmit="return confirm('Hapus?')">@csrf @method('DELETE')<button class="icon-btn danger"><i class="fa-solid fa-trash"></i></button></form></div></td></tr>@endforeach
</table></div>
@endsection
