@extends('admin.layout')
@section('title','Akun Pengelola')
@section('crumb','Admin utama melihat semua akun, pengelola hanya sesama pengelola')
@section('content')
<div class="card"><div style="overflow-x:auto"><table><tr><th>Nama</th><th>Peran</th><th>Status</th><th>Terakhir login</th><th>Aksi</th></tr>
@foreach($users as $u)<tr><td><div style="display:flex;gap:10px;align-items:center"><div class="avatar" style="width:34px;height:34px;font-size:13px">{{ strtoupper(substr($u->name,0,1)) }}</div><div><b>{{ $u->name }}</b> @if($u->is(auth()->user()))<span class="pill p-gold">Anda</span>@endif<br><small style="color:var(--muted)">{{ $u->email }}</small></div></div></td><td><span class="pill {{ $u->isUtama()?'p-gold':'p-blue' }}">{{ $u->roleLabel() }}</span></td>
<td>{!! $u->is_active?'<span class="pill p-green">aktif</span>':'<span class="pill p-red">nonaktif</span>' !!}</td>
<td style="font-size:12.5px">{{ $u->last_login_at?->format('d M Y H:i') ?? '-' }}</td>
<td><div class="actions"><a class="icon-btn" href="{{ route('admin.users.edit',$u) }}"><i class="fa-solid fa-pen"></i></a><form method="POST" action="{{ route('admin.users.toggle',$u) }}">@csrf<button class="icon-btn" title="Aktif/nonaktif"><i class="fa-solid fa-power-off"></i></button></form>@if(auth()->user()->isUtama() && !$u->isUtama())<form method="POST" action="{{ route('admin.users.resetSession',$u) }}" onsubmit="return confirm('Reset sesi {{ $u->name }}? Akun ini harus login ulang.')">@csrf<button class="icon-btn" title="Reset sesi (paksa login ulang)"><i class="fa-solid fa-arrows-rotate"></i></button></form>@endif @if(!$u->is(auth()->user()))<form method="POST" action="{{ route('admin.users.destroy',$u) }}" onsubmit="return confirm('Hapus akun ini?')">@csrf @method('DELETE')<button class="icon-btn danger"><i class="fa-solid fa-trash"></i></button></form>@endif</div></td></tr>@endforeach
</table></div><div class="pagination">{{ $users->links('vendor.pagination.admin') }}</div></div>
@endsection