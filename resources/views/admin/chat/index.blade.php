@extends('admin.layout')
@section('title','Chat Admin')
@section('crumb','Ruang obrolan antar pengelola website')
@section('content')
<style>
.chat-wrap{display:flex;background:var(--card);border:1px solid var(--line);border-radius:18px;overflow:hidden;height:calc(100vh - 200px);min-height:480px;box-shadow:0 2px 8px rgba(20,18,46,.04);position:relative}
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
.chat-head{display:flex;align-items:center;gap:12px;padding:11px 16px;background:var(--card);border-bottom:1px solid var(--line);z-index:5}
.chat-head .avatar{width:42px;height:42px;font-size:16px}
.chat-head .who b{font-size:15px;display:block}
.chat-head .who small{color:var(--muted);font-size:12px}
.chat-head .sp{margin-left:auto;display:flex;gap:6px}
.hbtn{width:36px;height:36px;border-radius:9px;border:1px solid var(--line);background:var(--top);color:var(--muted);cursor:pointer;display:inline-flex;align-items:center;justify-content:center;font-size:14px;text-decoration:none}
.hbtn:hover{color:var(--primary);border-color:var(--primary)}
.chat-back{display:none}
.chat-body{flex:1;overflow-y:auto;padding:20px;display:flex;flex-direction:column;gap:2px;background-image:radial-gradient(rgba(109,40,217,.06) 1px,transparent 1px);background-size:24px 24px}
.msg{display:flex;flex-direction:column;max-width:76%;margin-top:9px;position:relative;animation:msgIn .18s ease}
@keyframes msgIn{from{opacity:0;transform:translateY(6px)}to{opacity:1;transform:none}}
.msg.me{align-self:flex-end;align-items:flex-end}
.msg.them{align-self:flex-start;align-items:flex-start}
.bubble{padding:9px 13px;border-radius:16px;font-size:13.7px;line-height:1.5;word-break:break-word;box-shadow:0 1px 2px rgba(20,18,46,.06);max-width:100%}
.msg.me .bubble{background:linear-gradient(135deg,var(--primary),var(--primary2));color:#fff;border-bottom-right-radius:5px}
.msg.them .bubble{background:var(--card);color:var(--ink);border:1px solid var(--line);border-bottom-left-radius:5px}
.bubble .txt{white-space:pre-wrap}
.mtime{font-size:10px;color:var(--muted);margin-top:3px;padding:0 3px;display:flex;gap:5px;align-items:center}
.msg-date{text-align:center;margin:14px 0 2px}
.msg-date span{font-size:10.5px;font-weight:700;color:var(--muted);background:var(--card);border:1px solid var(--line);padding:3px 13px;border-radius:99px}
.quoted{border-left:3px solid rgba(255,255,255,.7);background:rgba(0,0,0,.12);border-radius:6px;padding:5px 9px;margin-bottom:6px;font-size:12px}
.msg.them .quoted{border-color:var(--primary);background:rgba(109,40,217,.08)}
.quoted b{display:block;font-size:11px;margin-bottom:1px}
.quoted span{opacity:.85;display:block;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:220px}
.att-img{max-width:240px;border-radius:12px;display:block;cursor:pointer}
.att-media{max-width:260px;border-radius:12px;display:block;margin-bottom:4px}
.att-audio{width:230px;display:block;margin-bottom:4px}
.att-file{display:flex;align-items:center;gap:10px;background:rgba(255,255,255,.14);border-radius:10px;padding:9px 11px;text-decoration:none;color:inherit;margin-bottom:4px}
.msg.them .att-file{background:rgba(109,40,217,.08)}
.att-file i{font-size:20px}.att-file .att-name{font-weight:700;font-size:12.5px}
.att-file .att-size{font-size:10.5px;opacity:.75;margin-left:auto}
.pick{position:absolute;left:-30px;top:50%;transform:translateY(-50%);width:20px;height:20px;border-radius:50%;border:2px solid var(--muted);display:none;align-items:center;justify-content:center;font-size:10px;color:#fff}
.msg.me .pick{left:auto;right:-30px}
.msg.sel .pick{display:flex;background:var(--primary);border-color:var(--primary)}
.msg.hit .bubble{box-shadow:0 0 0 2px #f59e0b}
.msg.hit.current .bubble{box-shadow:0 0 0 3px #f59e0b}
.pin-bar{display:none;align-items:center;gap:10px;padding:9px 16px;background:var(--card);border-bottom:1px solid var(--line);font-size:12.5px}
.pin-bar.on{display:flex}
.pin-bar i.fa-thumbtack{color:var(--primary)}
.pin-bar .ptxt{flex:1;min-width:0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.pin-bar .pmeta2{font-size:10.5px;color:var(--muted)}
.search-bar{display:none;align-items:center;gap:8px;padding:9px 16px;background:var(--card);border-bottom:1px solid var(--line);font-size:13px}
.search-bar.on{display:flex}
.search-bar input{flex:1;border:none;background:transparent;outline:none;color:var(--ink);font-family:inherit;font-size:13px}
.search-bar .scount{font-size:11.5px;color:var(--muted)}
.reply-bar{display:none;align-items:center;gap:10px;padding:8px 14px;background:var(--card);border-top:1px solid var(--line);border-left:4px solid var(--primary);font-size:12px}
.reply-bar.on{display:flex}
.reply-bar .rtxt{flex:1;min-width:0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.chat-form{display:flex;gap:8px;align-items:flex-end;padding:12px;background:var(--card);border-top:1px solid var(--line);position:relative}
.chat-form textarea{flex:1;resize:none;border:1.5px solid var(--line);border-radius:14px;padding:11px 15px;font-size:13.7px;font-family:inherit;background:var(--input);color:var(--ink);max-height:140px;outline:none;line-height:1.5}
.chat-form textarea:focus{border-color:var(--primary)}
.rbtn{width:46px;height:46px;border-radius:50%;border:none;background:var(--top);border:1px solid var(--line);color:var(--muted);font-size:17px;cursor:pointer;flex-shrink:0}
.rbtn:hover{color:var(--primary);border-color:var(--primary)}
.chat-send{width:46px;height:46px;border-radius:50%;border:none;background:linear-gradient(135deg,var(--primary),var(--primary2));color:#fff;font-size:16px;cursor:pointer;flex-shrink:0;transition:transform .15s;box-shadow:0 6px 16px rgba(109,40,217,.35)}
.chat-send:hover{transform:scale(1.06)}.chat-send:active{transform:scale(.9)}
.chat-none,.chat-empty-small{display:flex;flex-direction:column;align-items:center;justify-content:center;gap:10px;height:100%;color:var(--muted);font-size:13.5px;padding:28px;text-align:center}
.chat-none i,.chat-empty-small i{font-size:30px;color:var(--primary);opacity:.5}
/* floating menus */
.ctx{position:fixed;display:none;background:var(--top);border:1px solid var(--line);border-radius:12px;box-shadow:0 18px 44px rgba(20,18,46,.22);min-width:210px;z-index:90;overflow:hidden;padding:4px}
.ctx.on{display:block}
.ctx button,.ctx a{display:flex;align-items:center;gap:11px;width:100%;background:none;border:none;text-align:left;padding:10px 13px;font-size:13px;font-weight:600;color:var(--ink);cursor:pointer;text-decoration:none;border-radius:8px;font-family:inherit}
.ctx button:hover,.ctx a:hover{background:rgba(109,40,217,.1)}
.ctx button i{width:17px;color:var(--muted);font-size:13px}
.ctx button.danger,.ctx button.danger i{color:var(--red)}
.ctx .sep{height:1px;background:var(--line);margin:4px 6px}
.attach-menu{position:absolute;bottom:64px;left:8px;display:none;background:var(--top);border:1px solid var(--line);border-radius:14px;box-shadow:0 18px 44px rgba(20,18,46,.22);padding:6px;z-index:80;min-width:210px}
.attach-menu.on{display:block}
.attach-menu button{display:flex;align-items:center;gap:12px;width:100%;background:none;border:none;text-align:left;padding:11px 13px;font-size:13.5px;font-weight:600;color:var(--ink);cursor:pointer;border-radius:9px;font-family:inherit}
.attach-menu button:hover{background:rgba(109,40,217,.1)}
.attach-menu .ai{width:26px;height:26px;border-radius:8px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:12px}
.sel-bar{position:absolute;left:0;right:0;bottom:0;display:none;align-items:center;gap:12px;padding:12px 16px;background:var(--top);border-top:1px solid var(--line);z-index:70}
.sel-bar.on{display:flex}
.sel-bar .snum{font-weight:800;font-size:13px}
.sel-bar .sact{margin-left:auto;display:flex;gap:8px}
/* modals */
.cmodal{position:fixed;inset:0;background:rgba(10,8,26,.5);display:none;align-items:center;justify-content:center;z-index:100;padding:20px}
.cmodal.on{display:flex}
.cmodal .box{background:var(--top);border:1px solid var(--line);border-radius:16px;max-width:420px;width:100%;max-height:80vh;overflow:auto;box-shadow:0 24px 60px rgba(10,8,26,.4)}
.cmodal .box h3{font-size:15px;font-weight:800;padding:16px 18px;border-bottom:1px solid var(--line);display:flex;justify-content:space-between;align-items:center}
.cmodal .box .bd{padding:16px 18px;font-size:13.5px}
.cmodal .box .bd .row{display:flex;justify-content:space-between;gap:12px;padding:7px 0;border-bottom:1px solid var(--rowline)}
.cmodal .box .bd .row:last-child{border:none}
.cmodal .box .bd .row span:first-child{color:var(--muted)}
.fwd-item{display:flex;align-items:center;gap:11px;width:100%;background:none;border:none;padding:10px;border-radius:10px;cursor:pointer;text-align:left;color:var(--ink);font-family:inherit}
.fwd-item:hover{background:rgba(109,40,217,.1)}
.fwd-item .avatar{width:38px;height:38px;font-size:14px}
@media(max-width:820px){
  .chat-wrap{height:calc(100vh - 150px)}
  .chat-people{width:100%}
  .chat-main{display:none}
  .chat-wrap.has-active .chat-people{display:none}
  .chat-wrap.has-active .chat-main{display:flex}
  .chat-back{display:inline-flex}
  .msg{max-width:88%}
}
</style>

@if(!$active)
<div class="card"><div class="chat-none"><i class="fa-solid fa-user-group"></i>Belum ada admin lain untuk diajak chat.</div></div>
@else

<div class="chat-wrap has-active"
     id="chatWrap"
     data-partner="{{ $active->id }}"
     data-me="{{ auth()->id() }}"
     data-send="{{ route('admin.chat.send', $active) }}"
     data-poll="{{ route('admin.chat.poll', $active) }}"
     data-search="{{ route('admin.chat.search', $active) }}"
     data-clear="{{ route('admin.chat.clear', $active) }}">
  <aside class="chat-people">
    <div class="chat-search"><i class="fa-solid fa-magnifying-glass"></i><input id="peopleSearch" placeholder="Cari admin..."></div>
    <div class="chat-people-list" id="peopleList">
      @foreach($contacts as $c)
        @php $lm = $last[$c->id] ?? null; @endphp
        <a href="{{ route('admin.chat.show',$c) }}" class="person {{ $active->id===$c->id?'active':'' }}" data-name="{{ strtolower($c->name) }}">
          <div class="avatar">{{ strtoupper(substr($c->name,0,1)) }}</div>
          <div class="pinfo">
            <div class="pname">{{ $c->name }} <span class="role-tag">{{ $c->roleLabel() }}</span></div>
            <div class="plast">
              @if($lm)
                @if($lm->sender_id===auth()->id())<span style="color:var(--muted)">Anda: </span>@endif{{ \Illuminate\Support\Str::limit($lm->body ?: 'Lampiran',34) }}
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
      @endforeach
    </div>
  </aside>

  <section class="chat-main">
    <header class="chat-head">
      <button type="button" class="hbtn chat-back" onclick="document.getElementById('chatWrap').classList.remove('has-active')"><i class="fa-solid fa-arrow-left"></i></button>
      <div class="avatar">{{ strtoupper(substr($active->name,0,1)) }}</div>
      <div class="who"><b>{{ $active->name }}</b><small>{{ $active->roleLabel() }} &middot; {{ $active->email }}</small></div>
      <div class="sp">
        <button type="button" class="hbtn" id="btnSearch" title="Cari"><i class="fa-solid fa-magnifying-glass"></i></button>
        <button type="button" class="hbtn" id="btnMenu" title="Menu"><i class="fa-solid fa-ellipsis-vertical"></i></button>
      </div>
    </header>

    <div class="search-bar" id="searchBar">
      <i class="fa-solid fa-magnifying-glass" style="color:var(--muted)"></i>
      <input id="searchInput" placeholder="Cari di obrolan ini...">
      <span class="scount" id="searchCount">0/0</span>
      <button type="button" class="hbtn" id="searchPrev" style="width:30px;height:30px"><i class="fa-solid fa-chevron-up"></i></button>
      <button type="button" class="hbtn" id="searchNext" style="width:30px;height:30px"><i class="fa-solid fa-chevron-down"></i></button>
      <button type="button" class="hbtn" id="searchClose" style="width:30px;height:30px"><i class="fa-solid fa-xmark"></i></button>
    </div>

    <div class="pin-bar" id="pinBar">
      <i class="fa-solid fa-thumbtack"></i>
      <div class="ptxt" id="pinText"></div>
      <span class="pmeta2" id="pinMeta"></span>
      <button type="button" class="hbtn" id="pinUnpin" style="width:28px;height:28px" title="Lepas sematan"><i class="fa-solid fa-xmark"></i></button>
    </div>

    <div class="chat-body" id="chatBody"></div>

    <div class="reply-bar" id="replyBar">
      <i class="fa-solid fa-reply" style="color:var(--primary)"></i>
      <div class="rtxt" id="replyText"></div>
      <button type="button" class="hbtn" id="replyClose" style="width:28px;height:28px"><i class="fa-solid fa-xmark"></i></button>
    </div>

    <form class="chat-form" id="chatForm">
      <input type="file" id="fileInput" style="display:none">
      <button type="button" class="rbtn" id="btnAttach" title="Lampiran"><i class="fa-solid fa-paperclip"></i></button>
      <div class="attach-menu" id="attachMenu">
        <button type="button" data-kind="media" data-accept="image/*,video/*"><span class="ai" style="background:#8b5cf6"><i class="fa-solid fa-image"></i></span> Foto &amp; Video</button>
        <button type="button" data-kind="audio" data-accept="audio/*"><span class="ai" style="background:#f97316"><i class="fa-solid fa-headphones"></i></span> Audio</button>
        <button type="button" data-kind="doc" data-accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.zip"><span class="ai" style="background:#3b82f6"><i class="fa-solid fa-file-lines"></i></span> Dokumen</button>
      </div>
      <textarea id="chatInput" rows="1" placeholder="Tulis pesan... (Enter kirim, Shift+Enter baris baru)"></textarea>
      <button type="submit" class="chat-send" title="Kirim"><i class="fa-solid fa-paper-plane"></i></button>
    </form>
  </section>

  <div class="sel-bar" id="selBar">
    <span class="snum" id="selNum">0 dipilih</span>
    <div class="sact">
      <button type="button" class="hbtn" id="selForward" title="Teruskan"><i class="fa-solid fa-share"></i></button>
      <button type="button" class="hbtn" id="selDelete" title="Hapus" style="color:var(--red)"><i class="fa-solid fa-trash"></i></button>
      <button type="button" class="hbtn" id="selClose" title="Batal"><i class="fa-solid fa-xmark"></i></button>
    </div>
  </div>
</div>

{{-- konteks menu pesan --}}
<div class="ctx" id="ctxMenu">
  <button data-act="info"><i class="fa-solid fa-circle-info"></i> Info pesan</button>
  <button data-act="reply"><i class="fa-solid fa-reply"></i> Balas</button>
  <button data-act="copy"><i class="fa-regular fa-clipboard"></i> Salin</button>
  <button data-act="forward"><i class="fa-solid fa-share"></i> Teruskan</button>
  <button data-act="pin"><i class="fa-solid fa-thumbtack"></i> Sematkan</button>
  <button data-act="star"><i class="fa-regular fa-star"></i> Beri bintang</button>
  <button data-act="select"><i class="fa-regular fa-square-check"></i> Pilih</button>
  <div class="sep"></div>
  <button data-act="delete" class="danger"><i class="fa-solid fa-trash"></i> Hapus</button>
</div>

{{-- menu header --}}
<div class="ctx" id="headMenu">
  <button data-act="contact"><i class="fa-solid fa-circle-info"></i> Info kontak</button>
  <button data-act="search"><i class="fa-solid fa-magnifying-glass"></i> Cari</button>
  <button data-act="select"><i class="fa-regular fa-square-check"></i> Pilih pesan</button>
  @if(auth()->user()->isUtama())
  <a href="{{ route('admin.chat.monitor') }}"><i class="fa-solid fa-eye"></i> Pantau chat pengelola</a>
  @endif
  <div class="sep"></div>
  <button data-act="clear"><i class="fa-solid fa-broom"></i> Bersihkan obrolan</button>
  <button data-act="deletechat" class="danger"><i class="fa-solid fa-trash"></i> Hapus obrolan</button>
</div>

{{-- modal umum --}}
<div class="cmodal" id="modal">
  <div class="box">
    <h3><span id="modalTitle">Info</span><button type="button" class="hbtn" id="modalClose" style="width:30px;height:30px"><i class="fa-solid fa-xmark"></i></button></h3>
    <div class="bd" id="modalBody"></div>
  </div>
</div>

@endif
@endsection

@if($active)
@section('scripts')
@php
$chatUrls = [
    'star' => route('admin.chat.star', ['message' => '__ID__']),
    'pin' => route('admin.chat.pin', ['message' => '__ID__']),
    'forward' => route('admin.chat.forward', ['message' => '__ID__']),
    'delete' => route('admin.chat.message.destroy', ['message' => '__ID__']),
];
$chatContacts = $contacts->map(fn ($c) => ['id' => $c->id, 'name' => $c->name])->values();
@endphp
<script>
window.__CHAT = {
  urls: @json($chatUrls),
  contacts: @json($chatContacts),
  messages: @json($messages),
  pinned: @json($pinnedPayload),
  pinnedCount: {{ $pinnedCount }},
};
</script>
<script>
(function(){
  var wrap=document.getElementById('chatWrap');
  var body=document.getElementById('chatBody');
  var form=document.getElementById('chatForm');
  var input=document.getElementById('chatInput');
  var csrf='{{ csrf_token() }}';
  var ME=+wrap.dataset.me, PARTNER=+wrap.dataset.partner;
  var URLS=window.__CHAT.urls;
  var CONTACTS=window.__CHAT.contacts;
  var sendUrl=wrap.dataset.send, pollUrl=wrap.dataset.poll, searchUrl=wrap.dataset.search, clearUrl=wrap.dataset.clear;
  var store={}, lastId=0, lastDate=null, replyTo=null, ctxTarget=null, selecting=false, selected={};

  function esc(s){return String(s==null?'':s).replace(/[&<>"']/g,function(c){return{'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c];});}
  function fmtSize(b){if(!b)return '';var u=['B','KB','MB','GB'],i=0;while(b>=1024&&i<3){b/=1024;i++;}return b.toFixed(i?1:0)+' '+u[i];}
  function attHtml(a,t){
    if(!a)return '';
    if(t==='image')return '<img class="att-img" src="'+a.url+'" alt="" onclick="window.open(this.src)">';
    if(t==='video')return '<video class="att-media" controls src="'+a.url+'"></video>';
    if(t==='audio')return '<audio class="att-audio" controls src="'+a.url+'"></audio>';
    return '<a class="att-file" href="'+a.url+'" target="_blank" download><i class="fa-solid fa-file-lines"></i><span class="att-name">'+esc(a.name||'Dokumen')+'</span><span class="att-size">'+fmtSize(a.size)+'</span></a>';
  }
  function renderMsg(m){
    store[m.id]=m;
    var w=document.createElement('div');
    w.className='msg '+(m.mine?'me':'them'); w.dataset.id=m.id;
    var chk=document.createElement('span'); chk.className='pick'; chk.innerHTML='<i class="fa-solid fa-check"></i>';
    var b=document.createElement('div'); b.className='bubble';
    var inner='';
    if(m.reply) inner+='<div class="quoted"><b>'+esc(m.reply.name)+'</b><span>'+esc(m.reply.body)+'</span></div>';
    if(m.attachment) inner+=attHtml(m.attachment,m.type);
    if(m.body) inner+='<div class="txt">'+esc(m.body)+'</div>';
    b.innerHTML=inner;
    var mt=document.createElement('div'); mt.className='mtime';
    mt.innerHTML=(m.starred?'<i class="fa-solid fa-star" style="color:#f59e0b"></i>':'')+'<span>'+m.time+'</span>'+(m.pinned?' <i class="fa-solid fa-thumbtack" style="color:var(--primary)"></i>':'');
    w.appendChild(chk); w.appendChild(b); w.appendChild(mt);
    if(selected[m.id]) w.classList.add('sel');
    return w;
  }
  function maybeDate(m,parent){
    if(m.date!==lastDate){
      var d=document.createElement('div'); d.className='msg-date'; d.dataset.date=m.date;
      d.innerHTML='<span>'+m.date+'</span>'; parent.appendChild(d); lastDate=m.date;
    }
  }
  function appendMsg(m){ if(body.querySelector('.msg[data-id="'+m.id+'"]'))return; maybeDate(m,body); body.appendChild(renderMsg(m)); lastId=Math.max(lastId,m.id); }
  function renderAll(list){ body.innerHTML=''; lastDate=null; lastId=0; list.forEach(function(m){ maybeDate(m,body); body.appendChild(renderMsg(m)); lastId=Math.max(lastId,m.id); }); updatePin(); scroll(); }
  function scroll(){ body.scrollTop=body.scrollHeight; }

  renderAll(window.__CHAT.messages);
  window.__pinned=window.__CHAT.pinned; window.__pinnedCount=window.__CHAT.pinnedCount;
  function updatePin(){
    var bar=document.getElementById('pinBar');
    if(window.__pinned){
      bar.classList.add('on');
      document.getElementById('pinText').textContent=(window.__pinned.body||'Lampiran');
      document.getElementById('pinMeta').textContent=window.__pinned.time+(window.__pinnedCount>1?' • '+window.__pinnedCount+' disematkan':'');
      bar.dataset.id=window.__pinned.id;
    } else { bar.classList.remove('on'); }
  }
  updatePin();

  // ---- kirim ----
  async function doSend(fd){
    try{
      var r=await fetch(sendUrl,{method:'POST',headers:{'X-CSRF-TOKEN':csrf,'X-Requested-With':'XMLHttpRequest','Accept':'application/json'},body:fd});
      var d=await r.json();
      if(d.ok&&d.message){ appendMsg(d.message); scroll(); }
    }catch(_){}
  }
  form.addEventListener('submit',async function(e){
    e.preventDefault();
    var val=input.value.trim();
    var hasFile=fileInput.files.length>0;
    if(!val&&!hasFile)return;
    var fd=new FormData();
    if(val)fd.append('body',val);
    if(replyTo)fd.append('reply_to_id',replyTo.id);
    if(hasFile)fd.append('attachment',fileInput.files[0]);
    input.value=''; input.style.height='auto'; fileInput.value=''; clearReply();
    await doSend(fd);
  });
  input.addEventListener('input',function(){ input.style.height='auto'; input.style.height=Math.min(input.scrollHeight,140)+'px'; });
  input.addEventListener('keydown',function(e){ if(e.key==='Enter'&&!e.shiftKey){ e.preventDefault(); if(form.requestSubmit)form.requestSubmit();else form.dispatchEvent(new Event('submit',{cancelable:true})); }});

  // ---- lampiran ----
  var fileInput=document.getElementById('fileInput');
  var attachMenu=document.getElementById('attachMenu');
  document.getElementById('btnAttach').addEventListener('click',function(e){ e.stopPropagation(); attachMenu.classList.toggle('on'); });
  attachMenu.querySelectorAll('button').forEach(function(btn){
    btn.addEventListener('click',function(){ fileInput.setAttribute('accept',btn.dataset.accept); fileInput.click(); attachMenu.classList.remove('on'); });
  });
  fileInput.addEventListener('change',function(){ if(fileInput.files.length){ form.requestSubmit?form.requestSubmit():form.dispatchEvent(new Event('submit',{cancelable:true})); }});

  // ---- reply ----
  var replyBar=document.getElementById('replyBar');
  function setReply(m){ replyTo=m; replyBar.classList.add('on'); document.getElementById('replyText').textContent=m.body||'Lampiran'; input.focus(); }
  function clearReply(){ replyTo=null; replyBar.classList.remove('on'); }
  document.getElementById('replyClose').addEventListener('click',clearReply);

  // ---- polling ----
  async function poll(){
    try{
      var r=await fetch(pollUrl+'?after='+lastId,{headers:{'X-Requested-With':'XMLHttpRequest','Accept':'application/json','X-Ping':'1'}});
      if(!r.ok)return; var d=await r.json();
      if(d.ok&&d.messages&&d.messages.length){ var add=false; d.messages.forEach(function(m){ if(!store[m.id]){appendMsg(m);add=true;} }); if(add)scroll(); }
    }catch(_){}
  }
  var pollTimer=null;
  function pollLoop(){ clearInterval(pollTimer); pollTimer=setInterval(poll, document.hidden?15000:2500); }
  document.addEventListener('visibilitychange',function(){ pollLoop(); if(!document.hidden) poll(); });
  pollLoop();

  // ---- konteks menu ----
  var ctx=document.getElementById('ctxMenu');
  function openCtx(x,y,m){
    ctxTarget=m; ctx.classList.add('on');
    var w=ctx.offsetWidth,h=ctx.offsetHeight;
    ctx.style.left=Math.min(x,window.innerWidth-w-8)+'px';
    ctx.style.top=Math.min(y,window.innerHeight-h-8)+'px';
  }
  function closeCtx(){ ctx.classList.remove('on'); }
  body.addEventListener('contextmenu',function(e){ var w=e.target.closest('.msg'); if(!w)return; e.preventDefault(); openCtx(e.clientX,e.clientY,store[w.dataset.id]); });
  var pressTimer=null;
  body.addEventListener('touchstart',function(e){ var w=e.target.closest('.msg'); if(!w)return; var t=e.touches[0]; pressTimer=setTimeout(function(){ openCtx(t.clientX,t.clientY,store[w.dataset.id]); },500); },{passive:true});
  ['touchend','touchmove'].forEach(function(ev){ body.addEventListener(ev,function(){ if(pressTimer){clearTimeout(pressTimer);pressTimer=null;} },{passive:true}); });

  ctx.addEventListener('click',async function(e){
    var btn=e.target.closest('button'); if(!btn)return; var act=btn.dataset.act; var m=ctxTarget; closeCtx();
    if(!m)return;
    if(act==='reply'){ setReply(m); }
    else if(act==='copy'){ try{ await navigator.clipboard.writeText(m.body||''); }catch(_){} }
    else if(act==='info'){ infoMsg(m); }
    else if(act==='forward'){ forwardModal([m.id]); }
    else if(act==='pin'){ await api(URLS.pin.replace('__ID__',m.id)); location.reload(); }
    else if(act==='star'){ var r=await api(URLS.star.replace('__ID__',m.id)); if(r&&r.ok){ m.starred=r.starred; var w=body.querySelector('.msg[data-id="'+m.id+'"] .mtime'); if(w)w.innerHTML=(m.starred?'<i class="fa-solid fa-star" style="color:#f59e0b"></i>':'')+'<span>'+m.time+'</span>'; } }
    else if(act==='select'){ startSelect(m.id); }
    else if(act==='delete'){ if(confirm('Hapus pesan ini untuk semua?')){ var d=await api(URLS.delete.replace('__ID__',m.id),'DELETE'); if(d&&d.ok){ var w=body.querySelector('.msg[data-id="'+m.id+'"]'); if(w)w.remove(); } } }
  });

  // ---- menu header ----
  var headMenu=document.getElementById('headMenu');
  document.getElementById('btnMenu').addEventListener('click',function(e){ e.stopPropagation(); headMenu.classList.toggle('on'); if(headMenu.classList.contains('on')){ var r=this.getBoundingClientRect(); headMenu.style.left=Math.min(r.right-headMenu.offsetWidth,window.innerWidth-headMenu.offsetWidth-8)+'px'; headMenu.style.top=(r.bottom+6)+'px'; } });
  headMenu.addEventListener('click',function(e){
    var a=e.target.closest('button,a'); if(!a)return; var act=a.dataset.act; headMenu.classList.remove('on');
    if(act==='contact'){ infoContact(); }
    else if(act==='search'){ openSearch(); }
    else if(act==='select'){ startSelect(); }
    else if(act==='clear'||act==='deletechat'){ if(confirm('Hapus seluruh pesan di obrolan ini?')){ var f=document.createElement('form'); f.method='POST'; f.action=clearUrl; var t=document.createElement('input'); t.type='hidden'; t.name='_token'; t.value=csrf; f.appendChild(t); document.body.appendChild(f); f.submit(); } }
  });
  document.getElementById('btnSearch').addEventListener('click',openSearch);

  // ---- unduh/putar tidak bentrok; klik luar tutup menu ----
  document.addEventListener('click',function(e){ if(ctxTarget&&!ctx.contains(e.target))closeCtx(); if(!headMenu.contains(e.target)&&e.target.id!=='btnMenu')headMenu.classList.remove('on'); if(!attachMenu.contains(e.target)&&e.target.id!=='btnAttach')attachMenu.classList.remove('on'); });
  document.addEventListener('keydown',function(e){ if(e.key==='Escape'){ closeCtx(); headMenu.classList.remove('on'); attachMenu.classList.remove('on'); closeSearch(); } });

  // ---- pilih pesan ----
  var selBar=document.getElementById('selBar');
  function startSelect(id){ selecting=true; wrap.classList.add('selecting'); selBar.classList.add('on'); if(id){ selected[id]=true; var w=body.querySelector('.msg[data-id="'+id+'"]'); if(w)w.classList.add('sel'); } updSel(); }
  function stopSelect(){ selecting=false; selected={}; selBar.classList.remove('on'); wrap.classList.remove('selecting'); body.querySelectorAll('.msg.sel').forEach(function(w){w.classList.remove('sel');}); }
  function updSel(){ var n=Object.keys(selected).length; document.getElementById('selNum').textContent=n+' dipilih'; }
  body.addEventListener('click',function(e){ if(!selecting)return; var w=e.target.closest('.msg'); if(!w)return; var id=+w.dataset.id; selected[id]=!selected[id]; w.classList.toggle('sel',selected[id]); updSel(); });
  document.getElementById('selClose').addEventListener('click',stopSelect);
  document.getElementById('selDelete').addEventListener('click',async function(){ var ids=Object.keys(selected); if(!ids.length)return; if(!confirm('Hapus '+ids.length+' pesan untuk semua?'))return; for(var i=0;i<ids.length;i++){ await api(URLS.delete.replace('__ID__',ids[i]),'DELETE'); var w=body.querySelector('.msg[data-id="'+ids[i]+'"]'); if(w)w.remove(); } stopSelect(); });
  document.getElementById('selForward').addEventListener('click',function(){ var ids=Object.keys(selected); if(ids.length)forwardModal(ids); });

  // ---- info & forward modals ----
  var modal=document.getElementById('modal');
  function openModal(title,html){ document.getElementById('modalTitle').textContent=title; document.getElementById('modalBody').innerHTML=html; modal.classList.add('on'); }
  document.getElementById('modalClose').addEventListener('click',function(){ modal.classList.remove('on'); });
  modal.addEventListener('click',function(e){ if(e.target===modal)modal.classList.remove('on'); });
  function infoMsg(m){
    var rows='<div class="row"><span>Dikirim</span><b>'+m.date+' '+m.time+'</b></div>'+
      '<div class="row"><span>Pengirim</span><b>'+(m.mine?'Anda':'{{ $active->name }}')+'</b></div>'+
      '<div class="row"><span>Dibaca</span><b>'+(m.mine?(m.read?'Sudah dibaca':'Belum dibaca'):'-')+'</b></div>'+
      '<div class="row"><span>Jenis</span><b>'+m.type+'</b></div>'+
      (m.body?'<div class="row"><span>Isi</span><span style="text-align:right">'+esc(m.body)+'</span></div>':'');
    openModal('Info pesan',rows);
  }
  function infoContact(){
    openModal('Info kontak',
      '<div style="text-align:center;margin-bottom:12px"><div class="avatar" style="width:64px;height:64px;font-size:24px;margin:0 auto 8px">{{ strtoupper(substr($active->name,0,1)) }}</div><b style="font-size:15px">{{ $active->name }}</b></div>'+
      '<div class="row"><span>Peran</span><b>{{ $active->roleLabel() }}</b></div>'+
      '<div class="row"><span>Email</span><b>{{ $active->email }}</b></div>'+
      '<div class="row"><span>Terakhir login</span><b>{{ $active->last_login_at?->locale("id")->translatedFormat("d M Y H:i") ?? "-" }}</b></div>');
  }
  function forwardModal(ids){
    var html=CONTACTS.map(function(c){ return '<button class="fwd-item" data-id="'+c.id+'"><span class="avatar">'+esc(c.name.charAt(0).toUpperCase())+'</span><span style="font-weight:700">'+esc(c.name)+'</span></button>'; }).join('');
    openModal('Teruskan ke...','<div id="fwdList">'+(html||'<p style="color:var(--muted)">Tidak ada tujuan.</p>')+'</div>');
    document.getElementById('fwdList').addEventListener('click',async function(e){ var b=e.target.closest('.fwd-item'); if(!b)return; var tid=b.dataset.id; for(var i=0;i<ids.length;i++){ await api(URLS.forward.replace('__ID__',ids[i]),'POST',{target_id:tid}); } modal.classList.remove('on'); if(selecting)stopSelect(); });
  }

  // ---- pencarian dalam obrolan ----
  var searchBar=document.getElementById('searchBar'), searchHits=[], searchIdx=-1;
  function openSearch(){ searchBar.classList.add('on'); document.getElementById('searchInput').focus(); }
  function closeSearch(){ searchBar.classList.remove('on'); document.getElementById('searchInput').value=''; clearHits(); }
  function clearHits(){ body.querySelectorAll('.msg.hit').forEach(function(w){w.classList.remove('hit','current');}); searchHits=[]; searchIdx=-1; document.getElementById('searchCount').textContent='0/0'; }
  document.getElementById('searchClose').addEventListener('click',closeSearch);
  var sTimer=null;
  document.getElementById('searchInput').addEventListener('input',function(){ var q=this.value; clearTimeout(sTimer); sTimer=setTimeout(function(){ runSearch(q); },250); });
  async function runSearch(q){
    clearHits(); if(q.trim().length<2)return;
    try{
      var r=await fetch(searchUrl+'?q='+encodeURIComponent(q),{headers:{'X-Requested-With':'XMLHttpRequest','Accept':'application/json','X-Ping':'1'}});
      var d=await r.json(); if(!d.ok)return;
      searchHits=d.ids.map(function(id){return body.querySelector('.msg[data-id="'+id+'"]');}).filter(Boolean);
      searchHits.forEach(function(w){w.classList.add('hit');});
      if(searchHits.length){ searchIdx=0; focusHit(); } else { document.getElementById('searchCount').textContent='0/0'; }
    }catch(_){}
  }
  function focusHit(){ searchHits.forEach(function(w){w.classList.remove('current');}); var w=searchHits[searchIdx]; if(w){ w.classList.add('current'); w.scrollIntoView({block:'center'}); } document.getElementById('searchCount').textContent=(searchHits.length?(searchIdx+1):0)+'/'+searchHits.length; }
  document.getElementById('searchNext').addEventListener('click',function(){ if(!searchHits.length)return; searchIdx=(searchIdx+1)%searchHits.length; focusHit(); });
  document.getElementById('searchPrev').addEventListener('click',function(){ if(!searchHits.length)return; searchIdx=(searchIdx-1+searchHits.length)%searchHits.length; focusHit(); });

  // ---- pinned ----
  document.getElementById('pinBar').addEventListener('click',function(e){ if(e.target.closest('#pinUnpin'))return; var id=this.dataset.id; var w=body.querySelector('.msg[data-id="'+id+'"]'); if(w)w.scrollIntoView({block:'center'}); });
  document.getElementById('pinUnpin').addEventListener('click',async function(){ var id=document.getElementById('pinBar').dataset.id; await api(URLS.pin.replace('__ID__',id)); location.reload(); });

  // ---- helper fetch ----
  async function api(url,method,data){
    try{
      var opt={method:method||'POST',headers:{'X-CSRF-TOKEN':csrf,'X-Requested-With':'XMLHttpRequest','Accept':'application/json'}};
      if(data){ opt.headers['Content-Type']='application/json'; opt.body=JSON.stringify(data); }
      var r=await fetch(url,opt); return await r.json();
    }catch(_){ return null; }
  }
})();
</script>
<script>
(function(){ var s=document.getElementById('peopleSearch'), list=document.getElementById('peopleList'); if(s&&list)s.addEventListener('input',function(){ var q=s.value.toLowerCase(); list.querySelectorAll('.person').forEach(function(p){ p.style.display=(p.dataset.name||'').indexOf(q)>-1?'':'none'; }); }); })();
</script>
@endsection
@endif
