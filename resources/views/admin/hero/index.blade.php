@extends('admin.layout')
@section('title','Hero Slides')
@section('content')
<div class="card"><table><tr><th>Gambar</th><th>Urutan</th><th>Label / Title</th><th>Aktif</th><th>Aksi</th></tr>
@foreach($slides as $s)<tr><td>@if($s->gambar)<img src="{{ $s->gambar }}" style="width:90px;height:55px;object-fit:cover;border-radius:8px" onerror="this.style.display='none'">@else<span style="color:var(--muted)">-</span>@endif</td><td>{{ $s->urutan }}</td><td><small style="color:var(--muted)">{{ $s->label }} • {{ $s->badge }}</small><br><b>{!! \Str::limit(strip_tags($s->title),70) !!}</b></td><td>{!! $s->is_aktif?'<span class="pill p-green">aktif</span>':'<span class="pill p-gray">off</span>' !!}</td><td><div class="actions"><a class="icon-btn" href="{{ route('admin.hero.edit',$s) }}"><i class="fa-solid fa-pen"></i></a><form method="POST" action="{{ route('admin.hero.destroy',$s) }}" onsubmit="return confirm('Hapus slide?')">@csrf @method('DELETE')<button class="icon-btn danger"><i class="fa-solid fa-trash"></i></button></form></div></td></tr>@endforeach
</table></div>
@endsection
