@extends('admin.layout')
@section('title','Akun Pengelola')
@section('crumb','Admin utama melihat semua akun, pengelola hanya sesama pengelola')
@section('content')
<div class="card"><div style="overflow-x:auto"><table><tr><th>Nama</th><th>Peran</th><th>Status</th><th>Terakhir login</th><th>Aksi</th></tr>
@foreach($users as $u)<tr><td><div style="display:flex;gap:10px;align-items:center"><div class="avatar" style="width:34px;height:34px;font-size:13px">{{ strtoupper(substr($u->name,0,1)) }}</div><div><b>{{ $u->name }}</b> @if($u->is(auth()->user()))<span class="pill p-gold">Anda</span>@endif<br><small style="color:var(--muted)">{{ $u->email }}</small></div></div></td><td><span class="pill {{ $u->isUtama()?'p-gold':'p-blue' }}">{{ $u->roleLabel() }}</span></td>
<td>{!! $u->is_active?'<span class="pill p-green">aktif</span>':'<span class="pill p-red">nonaktif</span>' !!}</td>
<td style="font-size:12.5px">{{ $u->last_login_at?->format('d M Y H:i') ?? '-' }}</td>
<td><div class="actions"><a class="icon-btn" href="{{ route('admin.users.edit',$u) }}"><i class="fa-solid fa-pen"></i></a><form method="POST" action="{{ route('admin.users.toggle',$u) }}">@csrf<button class="icon-btn" title="Aktif/nonaktif"><i class="fa-solid fa-power-off"></i></button></form>@if(!$u->is(auth()->user()))<form method="POST" action="{{ route('admin.users.destroy',$u) }}" onsubmit="return confirm('Hapus akun ini?')">@csrf @method('DELETE')<button class="icon-btn danger"><i class="fa-solid fa-trash"></i></button></form>@endif</div></td></tr>@endforeach
</table></div><div class="pagination">{{ $users->links('vendor.pagination.admin') }}</div></div>

@auth()->user()->isUtama()
<div class="card mt-4">
<div style="display:flex;align-items:center;gap:12px;background:#fef3c7;padding:14px;border-radius:10px">
<i class="fa-solid fa-triangle-exclamation text-amber-700"></i>
<div>
<b style="color:#92400e">Reset Semua Sesi</b>
<div style="font-size:12px;color:#92400e;margin-top:2px">Mengeset ulang semua sesi admin, semua aksa harus login ulang.</div>
</div>
</div>
<form method="POST" action="{{ route('admin.users.resetSessions') }}">@csrf
<button class="btn btn-danger" style="margin-top:8px" onclick="return confirm('Yakin ingin mereset semua sesi admin? Semua akun harus login ulang.')">Reset Semua Sesi Login</button>
</form>
</div>
@endauth
</div>
@endsection