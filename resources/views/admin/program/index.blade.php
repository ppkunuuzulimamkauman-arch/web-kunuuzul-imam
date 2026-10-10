@extends('admin.layout')
@section('title','Program Pondok')
@section('content')
<div class="card"><div style="overflow-x:auto"><table><tr><th>Urutan</th><th>Nama</th><th>Kategori</th><th>Aktif</th><th>Aksi</th></tr>
@foreach($programs as $p)<tr><td>{{ $p->urutan }}</td><td><b>{{ $p->nama }}</b><br><small style="color:var(--muted)">{{ \Str::limit($p->deskripsi_singkat,80) }}</small></td><td><span class="pill p-blue">{{ $p->kategori }}</span></td><td>{!! $p->is_aktif?'<span class="pill p-green">aktif</span>':'<span class="pill p-gray">nonaktif</span>' !!}</td><td><div class="actions"><a class="icon-btn" href="{{ route('admin.program.edit',$p) }}"><i class="fa-solid fa-pen"></i></a><form method="POST" action="{{ route('admin.program.destroy',$p) }}" onsubmit="return confirm('Hapus?')">@csrf @method('DELETE')<button class="icon-btn danger"><i class="fa-solid fa-trash"></i></button></form></div></td></tr>@endforeach
</table></div><div class="pagination">{{ $programs->links('vendor.pagination.admin') }}</div></div>
@endsection
