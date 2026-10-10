@extends('admin.layout')
@section('title','Berita & Artikel')
@section('crumb','Kelola berita, artikel & pengumuman')
@section('content')
<div class="card">
  <form class="toolbar" method="GET"><div class="search"><input name="q" value="{{ request('q') }}" placeholder="Cari judul..."><select name="kategori"><option value="">Semua kategori</option>@foreach(['berita'=>'Berita','artikel'=>'Artikel','acara'=>'Acara','ppdb'=>'PPDB','prestasi'=>'Prestasi','pengumuman'=>'Pengumuman','maklumat'=>'Maklumat','dawuh'=>'Dawuh','bahtsul'=>'Bahtsul'] as $k=>$l)<option value="{{ $k }}" @selected(request('kategori')===$k)>{{ $l }}</option>@endforeach</select><button class="btn light">Cari</button></div></form>
  <div style="overflow-x:auto"><table><tr><th>Judul</th><th>Kategori</th><th>Dibaca</th><th>Status</th><th>Tgl</th><th>Aksi</th></tr>
  @foreach($beritas as $b)<tr><td style="font-weight:700;max-width:280px">{{ \Str::limit($b->judul,70) }}<br><small style="color:var(--muted)">/{{ $b->slug }} • {{ $b->penulis }}</small></td><td><span class="pill p-blue">{{ $b->kategori }}</span></td><td style="white-space:nowrap">👁 {{ number_format($b->views ?? 0) }}</td><td>{!! $b->is_published?'<span class="pill p-green">publish</span>':'<span class="pill p-gray">draft</span>' !!}</td><td style="white-space:nowrap">{{ ($b->published_at??$b->created_at)->format('d M Y') }}</td><td><div class="actions"><form method="POST" action="{{ route('admin.berita.toggle',$b) }}">@csrf<a class="icon-btn" href="{{ route('admin.berita.edit',$b) }}" title="Edit"><i class="fa-solid fa-pen"></i></a><button class="icon-btn" title="Toggle publish"><i class="fa-solid fa-eye"></i></button></form><form method="POST" action="{{ route('admin.berita.destroy',$b) }}" onsubmit="return confirm('Hapus berita ini?')">@csrf @method('DELETE')<button class="icon-btn danger" title="Hapus"><i class="fa-solid fa-trash"></i></button></form></div></td></tr>@endforeach
  </table></div>
  <div class="pagination">{{ $beritas->links('vendor.pagination.admin') }}</div>
</div>
@endsection
