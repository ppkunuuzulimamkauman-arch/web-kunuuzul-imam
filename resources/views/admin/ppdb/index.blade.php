@extends('admin.layout')
@section('title','PPDB Santri')
@section('crumb','Kelola pendaftaran santri baru')
@section('content')
<div class="card">
<form class="toolbar" method="GET"><div class="search"><input name="q" value="{{ request('q') }}" placeholder="Cari nama / nomor..."><select name="status"><option value="">Semua status</option>@foreach(['menunggu'=>'Menunggu','diproses'=>'Diproses','diterima'=>'Diterima','ditolak'=>'Ditolak','lunas'=>'Lunas'] as $k=>$l)<option value="{{ $k }}" @selected(request('status')===$k)>{{ $l }}</option>@endforeach</select><button class="btn light">Filter</button><a class="btn light" href="{{ route('admin.pendaftaran.export', request()->only('q','status')) }}">Export CSV</a></div></form>
<div style="overflow-x:auto"><table><tr><th>Nomor</th><th>Santri</th><th>Jenjang</th><th>Status</th><th>Aksi</th></tr>
@foreach($pendaftarans as $p)<tr><td style="font-weight:800;white-space:nowrap">{{ $p->nomor_pendaftaran }}</td><td><b>{{ $p->nama_lengkap }}</b><br><small style="color:var(--muted)">{{ $p->nama_wali }} • {{ $p->hp_wali }} • {{ $p->created_at->format('d M Y') }}</small></td><td>{{ $p->jenjang }}</td><td><span class="pill {{ $p->status==='diterima'?'p-green':($p->status==='ditolak'?'p-red':($p->status==='menunggu'?'p-gold':'p-blue')) }}">{{ $p->status }}</span></td><td><div class="actions"><a class="icon-btn" href="{{ route('admin.pendaftaran.show',$p) }}"><i class="fa-solid fa-eye"></i></a><form method="POST" action="{{ route('admin.pendaftaran.destroy',$p) }}" onsubmit="return confirm('Hapus data ini?')">@csrf @method('DELETE')<button class="icon-btn danger"><i class="fa-solid fa-trash"></i></button></form></div></td></tr>@endforeach
</table></div>
<div class="pagination">{{ $pendaftarans->links('vendor.pagination.admin') }}</div>
</div>
@endsection
