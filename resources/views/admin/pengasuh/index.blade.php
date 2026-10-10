@extends('admin.layout')
@section('title','Pengasuh & Struktur')
@section('content')
<div class="card"><table><tr><th>Foto</th><th>Nama</th><th>Aktif</th><th>Aksi</th></tr>
@foreach($pengasuhs as $p)<tr><td>@if($p->foto)<img src="{{ '/storage/'.$p->foto }}" style="width:46px;height:46px;object-fit:cover;border-radius:50%" onerror="this.style.display='none'">@else<div class="avatar">{{ substr($p->nama,0,1) }}</div>@endif</td><td><b>{{ $p->nama }}</b><br><small style="color:var(--muted)">{{ $p->jabatan }}</small></td><td>{!! $p->is_aktif?'<span class="pill p-green">aktif</span>':'<span class="pill p-gray">off</span>' !!}</td><td><div class="actions"><a class="icon-btn" href="{{ route('admin.pengasuh.edit',$p) }}"><i class="fa-solid fa-pen"></i></a><form method="POST" action="{{ route('admin.pengasuh.destroy',$p) }}" onsubmit="return confirm('Hapus?')">@csrf @method('DELETE')<button class="icon-btn danger"><i class="fa-solid fa-trash"></i></button></form></div></td></tr>@endforeach
</table></div>
@endsection
