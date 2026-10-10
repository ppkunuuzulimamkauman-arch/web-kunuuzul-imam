@extends('admin.layout')
@section('title','Galeri')
@section('content')
<div class="card"><div style="overflow-x:auto"><table><tr><th>Foto</th><th>Judul</th><th>Kategori</th><th>Aksi</th></tr>
@foreach($galeris as $g)<tr><td>@if($g->gambar)<img src="{{ str_starts_with($g->gambar,'/')||str_starts_with($g->gambar,'http')?$g->gambar:'/storage/'.$g->gambar }}" style="width:70px;height:50px;object-fit:cover;border-radius:8px" onerror="this.style.display='none'">@else<span style="color:var(--muted)">-</span>@endif</td><td><b>{{ $g->judul }}</b><br><small style="color:var(--muted)">{{ \Str::limit($g->keterangan,70) }}</small></td><td><span class="pill p-blue">{{ $g->kategori ?? '-' }}</span></td><td><div class="actions"><a class="icon-btn" href="{{ route('admin.galeri.edit',$g) }}"><i class="fa-solid fa-pen"></i></a><form method="POST" action="{{ route('admin.galeri.destroy',$g) }}" onsubmit="return confirm('Hapus?')">@csrf @method('DELETE')<button class="icon-btn danger"><i class="fa-solid fa-trash"></i></button></form></div></td></tr>@endforeach
</table></div><div class="pagination">{{ $galeris->links('vendor.pagination.admin') }}</div></div>
@endsection
