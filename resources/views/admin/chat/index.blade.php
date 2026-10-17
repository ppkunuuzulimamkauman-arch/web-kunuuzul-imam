@extends('admin.layout')
@section('title','Chat Admin')
@section('crumb','Ruang obrolan antar pengelola website')
@section('content')
<style>
.chat-wrap{display:flex;background:var(--card);border:1px solid var(--line);border-radius:18px;overflow:hidden;height:calc(100vh - 200px);min-height:480px;box-shadow:0 2px 8px rgba(20,18,46,.04)}
.chat-people{width:296px;flex-shrink:0;border-right:1px solid var(--line);display:flex;flex-direction:column;background:linear-gradient(180deg,rgba(109,40,217,.06),transparent 150px)}
.chat-search{display:flex;align-items:center;gap:9px;padding:14px 15px;border-bottom:1px solid var(--line);color:var(--muted)}
.chat-search input{flex:1;border:none;background:transparent;outline:none;font-size:13.5px;color:var(--ink);font-family:inherit}
.chat-people-list{overflow-y:auto;flex:1}
.person{display:flex;gap:11px;align-items:center;padding:12px 15px;text-decoration:none;color:var(--ink);border-bottom:1px solid var(--rowline);position:relative;transition:background .15s}
.person:last-child{border-bottom:none}
.person:hover{background:rgba(109,40,217,.05)}
.person.active{background:rgba(109,40,217,.1)}
.person.active::before{content:"";position:absolute;left:0;top:0;bottom:0;width:3px;background:var(--primary)}
.person .avatar{width:44px;height:44px;font-size:16px}
.pinfo{flex:1;min-width:0}
.pname{font-size:13.5px;font-weight:800;display:flex;align-items:center;gap:6px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.pname .role-tag{font-size:9px;flex-shrink:0}
.plast{font-size:12px;color:var(--muted);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;margin-top:3px}
.pmeta{display:flex;flex-direction:column;align-items:flex-end;gap:5px;flex-shrink:0}
.ptime{font-size:10.5px;color:var(--muted)}
.person .badge{min-width:18px;height:18px;font-size:10px}
.chat-main{flex:1;display:flex;flex-direction:column;min-width:0;background:var(--bg)}
.chat-head{display:flex;align-items:center;gap:12px;padding:13px 18px;background:var(--card);border-bottom:1px solid var(--line)}
.chat-head .avatar{width:42px;height:42px;font-size:16px}
.chat-head .who b{font-size:15px;display:block}
.chat-head .who small{color:var(--muted);font-size:12px}
.chat-back{display:none;align-items:center;justify-content:center;width:34px;height:34px;border-radius:9px;background:transparent;border:1px solid var(--line);color:var(--primary);cursor:pointer;margin-right:2px}
.chat-body{flex:1;overflow-y:auto;padding:22px 20px;display:flex;flex-direction:column;gap:2px;background-image:radial-gradient(rgba(109,40,217,.06) 1px,transparent 1px);background-size:24px 24px}
.msg{display:flex;flex-direction:column;max-width:74%;margin-top:9px;animation:msgIn .18s ease}
@keyframes msgIn{from{opacity:0;transform:translateY(6px)}to{opacity:1;transform:none}}
.msg.me{align-self:flex-end;align-items:flex-end}
.msg.them{align-self:flex-start;align-items:flex-start}
.bubble{padding:10px 14px;border-radius:16px;font-size:13.7px;line-height:1.5;word-break:break-word;white-space:pre-wrap;box-shadow:0 1px 2px rgba(20,18,46,.06)}
.msg.me .bubble{background:linear-gradient(135deg,var(--primary),var(--primary2));color:#fff;border-bottom-right-radius:5px}
.msg.them .bubble{background:var(--card);color:var(--ink);border:1px solid var(--line);border-bottom-left-radius:5px}
.mtime{font-size:10px;color:var(--muted);margin-top:3px;padding:0 3px}
.msg-date{text-align:center;margin:14px 0 2px}
.msg-date span{font-size:10.5px;font-weight:700;color:var(--muted);background:var(--card);border:1px solid var(--line);padding:3px 13px;border-radius:99px}
.chat-form{display:flex;gap:10px;align-items:flex-end;padding:13px;background:var(--card);border-top:1px solid var(--line)}
.chat-form textarea{flex:1;resize:none;border:1.5px solid var(--line);border-radius:14px;padding:11px 15px;font-size:13.7px;font-family:inherit;background:var(--input);color:var(--ink);max-height:140px;outline:none;line-height:1.5}
.chat-form textarea:focus{border-color:var(--primary)}
.chat-send{width:46px;height:46px;border-radius:50%;border:none;background:linear-gradient(135deg,var(--primary),var(--primary2));color:#fff;font-size:16px;cursor:pointer;flex-shrink:0;transition:transform .15s;box-shadow:0 6px 16px rgba(109,40,217,.35)}
.chat-send:hover{transform:scale(1.06)}.chat-send:active{transform:scale(.9)}
.chat-none,.chat-empty-small{display:flex;flex-direction:column;align-items:center;justify-content:center;gap:10px;height:100%;color:var(--muted);font-size:13.5px;padding:28px;text-align:center}
.chat-none i,.chat-empty-small i{font-size:30px;color:var(--primary);opacity:.5}
@media(max-width:820px){
  .chat-wrap{height:calc(100vh - 150px)}
  .chat-people{width:100%}
  .chat-main{display:none}
  .chat-wrap.has-active .chat-people{display:none}
  .chat-wrap.has-active .chat-main{display:flex}
  .chat-back{display:inline-flex}
}
</style>

<div class="chat-wrap {{ $active ? 'has-active' : '' }}">
  <aside class="chat-people">
    <div class="chat-search"><i class="fa-solid fa-magnifying-glass"></i><input id="peopleSearch" placeholder="Cari admin..."></div>
    <div class="chat-people-list" id="peopleList">
      @forelse($contacts as $c)
        @php $lm = $last[$c->id] ?? null; @endphp
        <a href="{{ route('admin.chat.show',$c) }}" class="person {{ ($active && $active->id===$c->id)?'active':'' }}" data-name="{{ strtolower($c->name) }}">
          <div class="avatar">{{ strtoupper(substr($c->name,0,1)) }}</div>
          <div class="pinfo">
            <div class="pname">{{ $c->name }} <span class="role-tag">{{ $c->roleLabel() }}</span></div>
            <div class="plast">
              @if($lm)
                @if($lm->sender_id===auth()->id())<span style="color:var(--muted)">Anda: </span>@endif{{ \Illuminate\Support\Str::limit($lm->body,34) }}
              @else
                Mulai percakapan
              @endif
            </div>
          </div>
          <div class="pmeta">
            <div class="ptime">{{ $lm?->created_at?->format('H:i') }}</div>
            @if(($unread[$c->id] ?? 0) > 0)<span class="badge">{{ $unread[$c->id] }}</span>@endif
          </div>
        </a>
      @empty
        <div class="chat-empty-small"><i class="fa-solid fa-user-group"></i>Belum ada admin lain untuk diajak chat.</div>
      @endforelse
    </div>
  </aside>

  <section class="chat-main">
    @if($active)
      <header class="chat-head">
        <button type="button" class="chat-back" onclick="document.querySelector('.chat-wrap').classList.remove('has-active')"><i class="fa-solid fa-arrow-left"></i></button>
        <div class="avatar">{{ strtoupper(substr($active->name,0,1)) }}</div>
        <div class="who"><b>{{ $active->name }}</b><small>{{ $active->roleLabel() }} &middot; {{ $active->email }}</small></div>
      </header>

      <div class="chat-body" id="chatBody" data-partner="{{ $active->id }}">
        @php $prevDate = null; @endphp
        @foreach($thread as $m)
          @php $d = $m->created_at->locale('id')->translatedFormat('d M Y'); @endphp
          @if($d !== $prevDate)
            <div class="msg-date" data-date="{{ $d }}"><span>{{ $d }}</span></div>
            @php $prevDate = $d; @endphp
          @endif
          <div class="msg {{ $m->sender_id===auth()->id()?'me':'them' }}" data-id="{{ $m->id }}">
            <div class="bubble">{{ $m->body }}</div>
            <div class="mtime">{{ $m->created_at->format('H:i') }}</div>
          </div>
        @endforeach
      </div>

      <form class="chat-form" id="chatForm" data-action="{{ route('admin.chat.send',$active) }}">
        <textarea id="chatInput" rows="1" placeholder="Tulis pesan... (Enter kirim, Shift+Enter baris baru)"></textarea>
        <button type="submit" class="chat-send" title="Kirim"><i class="fa-solid fa-paper-plane"></i></button>
      </form>
    @else
      <div class="chat-none"><i class="fa-solid fa-comments"></i>Pilih admin di samping untuk mulai mengobrol.</div>
    @endif
  </section>
</div>
@endsection

@section('scripts')
@if($active)
<script>
(function(){
  var body=document.getElementById('chatBody');
  var form=document.getElementById('chatForm');
  var input=document.getElementById('chatInput');
  var csrf='{{ csrf_token() }}';
  var sendUrl=form.dataset.action;
  var pollUrl='{{ route('admin.chat.poll', $active) }}';
  var lastId=0,lastDate=null;
  body.querySelectorAll('.msg').forEach(function(e){ lastId=Math.max(lastId, +e.dataset.id||0); });
  var dEls=body.querySelectorAll('.msg-date');
  if(dEls.length){ lastDate=dEls[dEls.length-1].dataset.date; }
  function scroll(){ body.scrollTop=body.scrollHeight; }
  function addMsg(m){
    if(body.querySelector('.msg[data-id="'+m.id+'"]')) return;
    if(m.date!==lastDate){
      var s=document.createElement('div');s.className='msg-date';s.dataset.date=m.date;
      var sp=document.createElement('span');sp.textContent=m.date;s.appendChild(sp);body.appendChild(s);lastDate=m.date;
    }
    var el=document.createElement('div');
    el.className='msg '+(m.mine?'me':'them');el.dataset.id=m.id;
    var b=document.createElement('div');b.className='bubble';b.textContent=m.body;
    var t=document.createElement('div');t.className='mtime';t.textContent=m.time;
    el.appendChild(b);el.appendChild(t);body.appendChild(el);
    lastId=Math.max(lastId,m.id);
  }
  scroll();
  form.addEventListener('submit',async function(e){
    e.preventDefault();
    var val=input.value.trim(); if(!val) return;
    input.value=''; input.style.height='auto';
    try{
      var r=await fetch(sendUrl,{method:'POST',headers:{'X-CSRF-TOKEN':csrf,'X-Requested-With':'XMLHttpRequest','Accept':'application/json','Content-Type':'application/json'},body:JSON.stringify({body:val})});
      var d=await r.json();
      if(d.ok&&d.message){ addMsg(d.message); scroll(); }
    }catch(_){}
  });
  input.addEventListener('input',function(){ input.style.height='auto'; input.style.height=Math.min(input.scrollHeight,140)+'px'; });
  input.addEventListener('keydown',function(e){ if(e.key==='Enter'&&!e.shiftKey){ e.preventDefault(); if(form.requestSubmit) form.requestSubmit(); else form.dispatchEvent(new Event('submit',{cancelable:true})); }});
  async function poll(){
    try{
      var r=await fetch(pollUrl+'?after='+lastId,{headers:{'X-Requested-With':'XMLHttpRequest','Accept':'application/json','X-Ping':'1'}});
      if(!r.ok) return; var d=await r.json();
      if(d.ok&&d.messages&&d.messages.length){ var added=false; d.messages.forEach(function(m){ addMsg(m); added=true; }); if(added) scroll(); }
    }catch(_){}
  }
  setInterval(poll,4000);
})();
</script>
@endif
<script>
(function(){
  var s=document.getElementById('peopleSearch'), list=document.getElementById('peopleList');
  if(s&&list) s.addEventListener('input',function(){
    var q=s.value.toLowerCase();
    list.querySelectorAll('.person').forEach(function(p){ p.style.display=(p.dataset.name||'').indexOf(q)>-1?'':'none'; });
  });
})();
</script>
@endsection
