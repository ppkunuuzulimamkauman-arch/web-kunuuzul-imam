@extends('admin.layout')
@section('title','Inbox Pesan')
@section('crumb',$belumDibaca.' pesan belum dibaca')
@section('content')
<div class="card"><div style="overflow-x:auto"><table><tr><th>Status</th><th>Dari</th><th>Subjek</th><th>Masuk</th><th>Aksi</th></tr>
@foreach($pesans as $m)<tr style="{{ !$m->is_dibaca?'background:#fffbeb':'' }}"><td>{!! !$m->is_dibaca?'<span class="pill p-red">baru</span>':($m->jawaban?'<span class="pill p-green">dijawab</span>':'<span class="pill p-gray">dibaca</span>') !!}@if($m->berita_id) <span class="pill p-blue">publish</span>@endif</td><td><b>{{ $m->nama }}</b><br><small style="color:var(--muted)">{{ $m->email }} • {{ $m->no_hp }}</small></td><td><a href="{{ route('admin.pesan.show',$m) }}">{{ $m->subjek ?? '(tanpa subjek)' }}</a><br><small style="color:var(--muted)">{{ \Str::limit($m->pesan,80) }}</small></td><td style="white-space:nowrap">{{ $m->created_at->diffForHumans() }}</td><td><div class="actions"><a class="icon-btn" href="{{ route('admin.pesan.show',$m) }}"><i class="fa-solid fa-eye"></i></a><form method="POST" action="{{ route('admin.pesan.destroy',$m) }}" onsubmit="return confirm('Hapus?')">@csrf @method('DELETE')<button class="icon-btn danger"><i class="fa-solid fa-trash"></i></button></form></div></td></tr>@endforeach
</table></div><div class="pagination">{{ $pesans->links('vendor.pagination.admin') }}</div></div>
@endsection
