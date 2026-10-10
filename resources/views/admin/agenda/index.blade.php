@extends('admin.layout')
@section('title','Agenda Pondok')
@section('crumb','Tampil di Majelis Terdekat & Agenda Pondok landing')
@section('content')
<div class="card"><div style="overflow-x:auto"><table><tr><th>Tanggal</th><th>Acara</th><th>Status</th><th>Aksi</th></tr>
@foreach($agendas as $a)<tr><td style="white-space:nowrap"><b>{{ $a->tanggal->format('d') }}</b> {{ \App\Models\Agenda::bulanSingkat($a->tanggal->month) }} {{ $a->tanggal->format('Y') }}</td><td><b>{{ $a->judul }}</b>@if($a->tempat)<br><small style="color:var(--muted)">📍 {{ $a->tempat }}</small>@endif</td><td>{!! $a->is_aktif?'<span class="pill p-green">tampil</span>':'<span class="pill p-gray">sembunyi</span>' !!}</td><td><div class="actions"><a class="icon-btn" href="{{ route('admin.agenda.edit',$a) }}"><i class="fa-solid fa-pen"></i></a><form method="POST" action="{{ route('admin.agenda.destroy',$a) }}" onsubmit="return confirm('Hapus agenda ini?')">@csrf @method('DELETE')<button class="icon-btn danger"><i class="fa-solid fa-trash"></i></button></form></div></td></tr>@endforeach
</table></div><div class="pagination">{{ $agendas->links('vendor.pagination.admin') }}</div></div>
@endsection
