@extends('admin.layout')
@section('title','Pantau Chat Pengelola')
@section('crumb','Mode baca saja — admin utama melihat obrolan antar admin')
@section('content')
<style>
.mon-head{display:flex;align-items:center;gap:10px;flex-wrap:wrap;margin-bottom:14px}
.mon-head select{border:1.5px solid var(--line);border-radius:10px;padding:9px 13px;font-size:13.5px;background:var(--top);color:var(--ink);font-family:inherit}
.mon-thread{background:var(--card);border:1px solid var(--line);border-radius:16px;padding:20px;max-height:calc(100vh - 300px);min-height:400px;overflow-y:auto;display:flex;flex-direction:column;gap:2px;background-image:radial-gradient(rgba(109,40,217,.06) 1px,transparent 1px);background-size:24px 24px}
.msg{display:flex;flex-direction:column;max-width:74%;margin-top:9px}
.msg.right{align-self:flex-end;align-items:flex-end}
.msg.left{align-self:flex-start}
.bubble{padding:9px 13px;border-radius:16px;font-size:13.5px;line-height:1.5;word-break:break-word;box-shadow:0 1px 2px rgba(20,18,46,.06)}
.msg.left .bubble{background:var(--bg);border:1px solid var(--line);color:var(--ink);border-bottom-left-radius:5px}
.msg.right .bubble{background:linear-gradient(135deg,var(--primary),var(--primary2));color:#fff;border-bottom-right-radius:5px}
.mname{font-size:10.5px;font-weight:800;color:var(--primary);margin-bottom:3px}
.mtime{font-size:10px;color:var(--muted);margin-top:3px}
.att-img{max-width:220px;border-radius:10px;display:block}
.att-file{display:inline-flex;align-items:center;gap:8px;text-decoration:underline;color:inherit}
</style>

<form class="mon-head" method="GET" action="{{ route('admin.chat.monitor') }}">
  <span style="font-weight:700;font-size:13.5px">Pantau percakapan:</span>
  <select name="a">
    @foreach($contacts as $c)<option value="{{ $c->id }}" @selected($pairA && $pairA->id===$c->id)>{{ $c->name }}</option>@endforeach
  </select>
  <span style="color:var(--muted)">↔</span>
  <select name="b">
    @foreach($contacts as $c)<option value="{{ $c->id }}" @selected($pairB && $pairB->id===$c->id)>{{ $c->name }}</option>@endforeach
  </select>
  <button class="btn" type="submit"><i class="fa-solid fa-eye"></i> Tampilkan</button>
  <span class="pill p-gold" style="margin-left:auto"><i class="fa-solid fa-lock"></i> Baca saja</span>
</form>

<div class="mon-thread">
  @forelse($thread as $m)
    <div class="msg {{ $m->sender_id===$pairA->id?'left':'right' }}">
      <div class="mname">{{ $m->sender_id===$pairA->id?$pairA->name:$pairB->name }}</div>
      <div class="bubble">
        @if($m->body)<div style="white-space:pre-wrap">{{ $m->body }}</div>@endif
        @if($m->attachment_path)
          @if($m->type==='image')
            <img class="att-img" src="{{ asset('storage/'.$m->attachment_path) }}" alt="">
          @elseif($m->type==='audio')
            <audio controls src="{{ asset('storage/'.$m->attachment_path) }}"></audio>
          @elseif($m->type==='video')
            <video controls style="max-width:220px;border-radius:10px" src="{{ asset('storage/'.$m->attachment_path) }}"></video>
          @else
            <a class="att-file" href="{{ asset('storage/'.$m->attachment_path) }}" target="_blank"><i class="fa-solid fa-file-lines"></i>{{ $m->attachment_name }}</a>
          @endif
        @endif
      </div>
      <div class="mtime">{{ $m->created_at->locale('id')->translatedFormat('d M Y H:i') }}</div>
    </div>
  @empty
    <div style="margin:auto;color:var(--muted);font-size:13.5px;text-align:center"><i class="fa-solid fa-comment-slash" style="font-size:28px;opacity:.4;display:block;margin-bottom:8px"></i>Belum ada percakapan antara kedua akun ini.</div>
  @endforelse
</div>
@endsection
