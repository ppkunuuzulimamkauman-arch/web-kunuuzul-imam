@extends('admin.layout')
@section('title','Isi Pesan')
@section('content')
<div class="card" style="max-width:760px">
<p style="font-size:12px;color:var(--muted)">{{ $pesan->created_at->format('d M Y H:i') }} • {{ $pesan->email }} • {{ $pesan->no_hp }}</p>
<h2 style="margin:6px 0">{{ $pesan->subjek ?? '(tanpa subjek)' }}</h2>
<p style="font-size:13px;color:var(--muted)">Dari: <b style="color:#243044">{{ $pesan->nama }}</b></p>
<div style="background:#f8fafc;border:1px solid var(--line);border-radius:12px;padding:16px;margin:14px 0;white-space:pre-line;line-height:1.7">{{ $pesan->pesan }}</div>
<div style="display:flex;gap:8px;flex-wrap:wrap">
@if($pesan->email)<a class="btn light" href="mailto:{{ $pesan->email }}?subject=Re: {{ $pesan->subjek }}"><i class="fa-solid fa-reply"></i> Balas Email</a>@endif
@if($pesan->no_hp)<a class="btn green" href="https://wa.me/{{ preg_replace('/[^0-9]/','',$pesan->no_hp) }}" target="_blank"><i class="fa-brands fa-whatsapp"></i> Balas WA</a>@endif
<a class="btn ghost" href="{{ route('admin.pesan.index') }}">← Inbox</a>
</div>
</div>
@endsection
