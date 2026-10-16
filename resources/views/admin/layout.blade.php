<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title','Dashboard') — Admin Kunuuzul Imam</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>try{document.documentElement.dataset.theme=localStorage.getItem('kunuuzul-theme')||'light';}catch(e){document.documentElement.dataset.theme='light';}</script>
<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:'Plus Jakarta Sans',system-ui,sans-serif}
:root{--side:#14122e;--side2:#1d1a45;--primary:#6d28d9;--primary2:#7c3aed;--bg:#f5f6fa;--card:#fff;--top:#fff;--input:#fbfcff;--line:#eceef5;--rowline:#f2f3f9;--track:#eef0f7;--muted:#8a8fa3;--ink:#1c1a33;--green:#16a34a;--red:#dc2626;--gold:#d9a441}
[data-theme="dark"]{--bg:#0e0c22;--card:#191736;--top:#191736;--input:#12102a;--line:#2b2854;--rowline:#262350;--track:#262350;--muted:#9c96c9;--ink:#edeafa}
body{background:var(--bg);color:var(--ink);display:flex;min-height:100vh}
/* ===== sidebar ===== */
.sidebar{width:232px;background:linear-gradient(180deg,var(--side),#0e0c24);color:#c9c6e8;position:fixed;inset:0 auto 0 0;display:flex;flex-direction:column;z-index:40}
.brand{display:flex;align-items:center;gap:10px;padding:20px 18px 14px}
.brand img{width:40px;height:auto;max-height:46px;object-fit:contain;flex-shrink:0;background:transparent}
.brand small{font-size:10px;font-weight:400;letter-spacing:.22em;color:#8f8bb8;display:block}
.brand b{font-size:16px;color:#fff;display:block;line-height:1.3}
.nav{padding:6px 12px;overflow-y:auto;flex:1}
.nav-label{font-size:10px;font-weight:800;letter-spacing:.14em;color:#7d78ad;margin:12px 10px 6px}
.nav a{display:flex;align-items:center;gap:11px;color:#b9b5dd;text-decoration:none;padding:10px 12px;border-radius:10px;font-size:13.5px;font-weight:600;margin-bottom:2px;position:relative}
.nav a i{width:18px;text-align:center;font-size:14px}
.nav a:hover{background:rgba(255,255,255,.06);color:#fff}
.nav a.active{background:var(--primary);color:#fff;box-shadow:0 6px 16px rgba(109,40,217,.4)}
.badge{margin-left:auto;background:#ef4444;color:#fff;font-size:10px;min-width:19px;height:19px;border-radius:999px;display:inline-flex;align-items:center;justify-content:center;padding:0 6px;font-weight:800}
.side-card{margin:12px;background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.09);border-radius:14px;padding:14px;font-size:12.5px}
.side-card b{color:#fff}
.side-card .btn{width:100%;justify-content:center;margin-top:10px}
.userbox{margin:0 12px 14px;padding:11px 12px;background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.09);border-radius:14px;display:flex;align-items:center;gap:10px}
.avatar{width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#a78bfa,#6d28d9);color:#fff;font-weight:800;display:flex;align-items:center;justify-content:center;flex-shrink:0}
/* ===== main ===== */
.main{margin-left:232px;flex:1;min-width:0}
.topbar{background:var(--top);border-bottom:1px solid var(--line);padding:14px 26px;display:flex;align-items:center;gap:14px;position:sticky;top:0;z-index:20}
.topbar h1{font-size:18px;font-weight:800}.topbar .crumb{font-size:12px;color:var(--muted)}
.searchbar{display:flex;align-items:center;gap:8px;background:var(--bg);border:1px solid var(--line);border-radius:10px;padding:8px 14px;min-width:280px;color:var(--muted);font-size:13px}
.searchbar input{border:none;background:transparent;outline:none;width:100%;font-size:13px;color:var(--ink)}
.icon-top{width:38px;height:38px;border-radius:10px;border:1px solid var(--line);background:var(--top);display:inline-flex;align-items:center;justify-content:center;color:var(--muted);position:relative;cursor:pointer;text-decoration:none;transition:transform .25s ease,color .25s ease,border-color .25s ease,background-color .4s ease}
.icon-top:hover{color:var(--primary);border-color:var(--primary)}
.icon-top:active{transform:scale(.88)}
.icon-top .dot{position:absolute;top:6px;right:7px;background:#ef4444;color:#fff;font-size:9px;font-weight:800;min-width:16px;height:16px;border-radius:999px;display:flex;align-items:center;justify-content:center;padding:0 4px}
/* Animasi pergantian tema: ikon berputar + seluruh UI fade warna halus */
#themeToggle i{display:inline-block}
#themeToggle.theme-spin i{animation:themeSpin .55s cubic-bezier(.34,1.4,.64,1)}
@keyframes themeSpin{0%{transform:rotate(0) scale(1)}40%{transform:rotate(180deg) scale(.4);opacity:.3}100%{transform:rotate(360deg) scale(1);opacity:1}}
body,.main,.content,.card,.topbar,.sidebar,.set-menu,input,select,textarea,table tr{transition:background-color .4s ease,color .4s ease,border-color .4s ease}
@media (prefers-reduced-motion:reduce){#themeToggle.theme-spin i{animation:none}body,.main,.content,.card,.topbar,.sidebar,.set-menu,input,select,textarea,table tr{transition:none}}
.btn{display:inline-flex;align-items:center;gap:8px;border:none;cursor:pointer;text-decoration:none;font-weight:700;font-size:13px;padding:10px 18px;border-radius:10px;background:var(--primary);color:#fff;white-space:nowrap}
.btn:hover{background:var(--primary2)}
.btn.light{background:#f1edfd;color:var(--primary)}
.btn.green{background:var(--green)} .btn.red{background:var(--red)} .btn.gold{background:var(--gold);color:#1c1a33} .btn.ghost{background:var(--top);color:var(--ink);border:1px solid var(--line)}
.dd{position:relative}.dd-menu{display:none;position:absolute;right:0;top:calc(100% + 8px);background:var(--top);border:1px solid var(--line);border-radius:12px;box-shadow:0 16px 40px rgba(20,18,46,.14);min-width:220px;overflow:hidden;z-index:50}.dd.open .dd-menu{display:block}.dd-menu a{display:flex;gap:10px;align-items:center;padding:11px 14px;font-size:13px;font-weight:600;color:var(--ink);text-decoration:none}.dd-menu a:hover{background:#f6f2ff}.dd-menu a i{color:var(--primary);width:18px}
.content{padding:24px 26px;max-width:1280px}
/* ===== CRM cards ===== */
.stats{display:grid;grid-template-columns:repeat(5,1fr);gap:14px}
@media(max-width:1200px){.stats{grid-template-columns:repeat(3,1fr)}}
@media(max-width:760px){.stats{grid-template-columns:1fr 1fr}}
.stat-card{background:var(--card);border:1px solid var(--line);border-radius:16px;padding:16px;display:flex;flex-direction:column;gap:4px;box-shadow:0 2px 8px rgba(20,18,46,.04)}
.stat-top{display:flex;justify-content:space-between;align-items:flex-start}
.stat-top small{font-size:12px;font-weight:700;color:var(--muted)}
.stat-ic{width:42px;height:42px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:17px;flex-shrink:0}
.stat-num{font-size:23px;font-weight:800;letter-spacing:-.5px}
.delta{font-size:12px;font-weight:700;color:var(--green)}
.delta small{color:var(--muted);font-weight:500}
.panel{background:var(--card);border:1px solid var(--line);border-radius:16px;padding:18px;box-shadow:0 2px 8px rgba(20,18,46,.04)}
.panel-head{display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;gap:10px}
.panel-head b{font-size:14.5px;font-weight:800}
.link{font-size:12.5px;font-weight:700;color:var(--primary);text-decoration:none}
.grid2{display:grid;grid-template-columns:1.6fr 1fr;gap:14px;margin-top:14px}
.grid2b{display:grid;grid-template-columns:1.4fr 1fr;gap:14px;margin-top:14px}
@media(max-width:1000px){.grid2,.grid2b{grid-template-columns:1fr}}
.pipe-row{display:flex;align-items:center;gap:10px;padding:9px 0;border-bottom:1px solid var(--rowline);font-size:13px}
.pipe-row:last-child{border:none}
.pipe-ic{width:34px;height:34px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:14px;flex-shrink:0}
.pipe-bar{height:6px;border-radius:99px;background:var(--track);margin-top:6px;overflow:hidden}
.pipe-bar span{display:block;height:100%;border-radius:99px}
.act-row{display:flex;gap:11px;padding:10px 0;border-bottom:1px solid var(--rowline);font-size:13px}
.act-row:last-child{border:none}
.act-ic{width:34px;height:34px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:13px;color:#fff;flex-shrink:0}
/* ===== generic (dipakai halaman CRUD) ===== */
.card{background:var(--card);border:1px solid var(--line);border-radius:16px;padding:18px;box-shadow:0 2px 8px rgba(20,18,46,.04);margin-bottom:14px}
table{width:100%;border-collapse:collapse;font-size:13.5px}th{text-align:left;font-size:11px;letter-spacing:.06em;text-transform:uppercase;color:var(--muted);padding:10px;border-bottom:1px solid var(--line)}td{padding:11px 10px;border-bottom:1px solid var(--rowline);vertical-align:middle}
.pill{display:inline-block;font-size:11px;font-weight:800;padding:4px 10px;border-radius:999px}
.p-green{background:#dcfce7;color:#166534}.p-red{background:#fee2e2;color:#991b1b}.p-gold{background:#fef3c7;color:#92400e}.p-blue{background:#dbeafe;color:#1e40af}.p-gray{background:#f1f5f9;color:#475569}
.alert{padding:12px 16px;border-radius:12px;margin-bottom:14px;font-size:13.5px;font-weight:600}
.alert.ok{background:#dcfce7;color:#14532d;border:1px solid #bbf7d0}.alert.err{background:#fee2e2;color:#7f1d1d;border:1px solid #fecaca}
.form-grid{display:grid;grid-template-columns:1fr 1fr;gap:14px}@media(max-width:760px){.form-grid{grid-template-columns:1fr}}
label.f{display:block;margin-bottom:12px;font-size:13px;font-weight:700}label.f input,label.f select,label.f textarea{margin-top:6px;width:100%;border:1.5px solid var(--line);border-radius:10px;padding:11px 13px;font-size:14px;font-weight:500;font-family:inherit;background:var(--input);color:var(--ink)}label.f textarea{min-height:120px;resize:vertical}label.f input:focus,label.f select:focus,label.f textarea:focus{outline:none;border-color:var(--primary)}
.check{display:flex;align-items:center;gap:8px;font-size:13.5px;font-weight:600}
.actions{display:flex;gap:6px;flex-wrap:wrap}.icon-btn{width:32px;height:32px;border-radius:9px;display:inline-flex;align-items:center;justify-content:center;text-decoration:none;border:1px solid var(--line);background:var(--top);color:var(--ink);font-size:13px;cursor:pointer}.icon-btn:hover{background:var(--primary);border-color:var(--primary);color:#fff}.icon-btn.danger:hover{background:var(--red);border-color:var(--red)}
.toolbar{display:flex;gap:10px;align-items:center;justify-content:space-between;margin-bottom:14px;flex-wrap:wrap}
.search{display:flex;gap:8px;flex-wrap:wrap}.search input,.search select{border:1.5px solid var(--line);border-radius:10px;padding:9px 13px;font-size:13.5px;background:var(--top);color:var(--ink)}
.role-tag{font-size:10.5px;font-weight:800;padding:3px 9px;border-radius:999px;background:#ede9fe;color:var(--primary);text-transform:uppercase;letter-spacing:.05em}
.pagination{display:flex;gap:6px;justify-content:center;margin-top:16px;flex-wrap:wrap}.pagination a,.pagination span{padding:8px 13px;border-radius:9px;background:var(--top);border:1px solid var(--line);text-decoration:none;font-size:13px;color:var(--ink);font-weight:700}.pagination a:hover{border-color:var(--primary);color:var(--primary)}.pagination span.active{background:var(--primary);border-color:var(--primary);color:#fff}
.burger{display:none;border:1px solid var(--line);background:var(--top);color:var(--ink);border-radius:10px;width:40px;height:40px;font-size:18px}@media(max-width:860px){.burger{display:block}}
.kv{display:grid;grid-template-columns:170px 1fr;gap:8px 14px;font-size:14px}.kv dt{color:var(--muted);font-weight:600}.kv dd{font-weight:600}
.grid{display:grid;gap:16px}.g4{grid-template-columns:repeat(4,1fr)}.g2{grid-template-columns:1fr 1fr}
@media(max-width:1000px){.g4{grid-template-columns:1fr 1fr}.g2{grid-template-columns:1fr}}
@media(max-width:860px){.sidebar{transform:translateX(-100%);transition:.3s}.sidebar.open{transform:none}.main{margin-left:0}.searchbar{display:none}.content{padding:16px}}
.stat{display:flex;gap:14px;align-items:center}.stat .ic{width:48px;height:48px;border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:20px;color:#fff;flex-shrink:0}.stat b{font-size:24px;display:block}.stat span{font-size:12.5px;color:var(--muted)}
</style>
</head>
<body>
<aside class="sidebar" id="sidebar">
  <div class="brand">
    <img src="{{ asset('images/logo-pondok.png') }}" alt="Logo PP Kunuuzul Imam Kauman">
    <div><small>PONDOK PESANTREN</small><b>KUNUUZUL IMAM KAUMAN</b></div>
  </div>
  <nav class="nav">
    <div class="nav-label">MENU UTAMA</div>
    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard')?'active':'' }}"><i class="fa-solid fa-house"></i> Dashboard</a>
    <a href="{{ route('admin.pendaftaran.index') }}" class="{{ request()->routeIs('admin.pendaftaran.*')?'active':'' }}"><i class="fa-solid fa-user-plus"></i> PPDB Santri @php $nTunggu = \App\Models\Pendaftaran::where('status','menunggu')->count(); @endphp @if($nTunggu>0)<span class="badge">{{ $nTunggu }}</span>@endif</a>
    <a href="{{ route('admin.pesan.index') }}" class="{{ request()->routeIs('admin.pesan.*')?'active':'' }}"><i class="fa-solid fa-inbox"></i> Inbox Pesan @php try{$nPesan=\App\Models\Pesan::where('is_dibaca',false)->count();}catch(\Throwable $e){$nPesan=0;} @endphp @if($nPesan>0)<span class="badge">{{ $nPesan }}</span>@endif</a>
    <div class="nav-label">KONTEN WEBSITE</div>
    <a href="{{ route('admin.berita.index') }}" class="{{ request()->routeIs('admin.berita.*')?'active':'' }}"><i class="fa-solid fa-newspaper"></i> Berita & Artikel</a>
    <a href="{{ route('admin.program.index') }}" class="{{ request()->routeIs('admin.program.*')?'active':'' }}"><i class="fa-solid fa-book-open"></i> Program</a>
    <a href="{{ route('admin.galeri.index') }}" class="{{ request()->routeIs('admin.galeri.*')?'active':'' }}"><i class="fa-solid fa-images"></i> Galeri</a>
    <a href="{{ route('admin.lembaga.index') }}" class="{{ request()->routeIs('admin.lembaga.*')?'active':'' }}"><i class="fa-solid fa-mosque"></i> Lembaga</a>
    <a href="{{ route('admin.organisasi.index') }}" class="{{ request()->routeIs('admin.organisasi.*')?'active':'' }}"><i class="fa-solid fa-sitemap"></i> Organisasi</a>
    <a href="{{ route('admin.pengasuh.index') }}" class="{{ request()->routeIs('admin.pengasuh.*')?'active':'' }}"><i class="fa-solid fa-users"></i> Pengasuh</a>
    <a href="{{ route('admin.agenda.index') }}" class="{{ request()->routeIs('admin.agenda.*')?'active':'' }}"><i class="fa-solid fa-calendar-days"></i> Agenda</a>
    <div class="nav-label">SISTEM</div>
    <a href="{{ route('admin.panduan') }}" class="{{ request()->routeIs('admin.panduan')?'active':'' }}"><i class="fa-solid fa-map"></i> Panduan Landing</a>
    <a href="{{ route('admin.setting.index') }}" class="{{ request()->routeIs('admin.setting.*')?'active':'' }}"><i class="fa-solid fa-gear"></i> Pengaturan</a>
    <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*')?'active':'' }}"><i class="fa-solid fa-user-shield"></i> Akun Admin</a>
  </nav>
  <div class="userbox">
    <div class="avatar">{{ strtoupper(substr(auth()->user()->name ?? 'A',0,1)) }}</div>
    <div style="flex:1;min-width:0"><div style="font-size:13px;font-weight:800;color:#fff;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ auth()->user()->name }}</div><div style="font-size:11px;color:#a5a1d6">Admin</div></div>
    <form method="POST" action="{{ route('admin.logout') }}">@csrf<button class="icon-top" title="Logout" style="background:transparent;border-color:rgba(255,255,255,.2);color:#fff;width:34px;height:34px"><i class="fa-solid fa-right-from-bracket"></i></button></form>
  </div>
</aside>
<div class="main">
  <div class="topbar">
    <button class="burger" onclick="document.getElementById('sidebar').classList.toggle('open')">☰</button>
    <div><h1>@yield('title','Dashboard')</h1><div class="crumb">@yield('crumb','Overview aktivitas pondok & website')</div></div>
    <div style="margin-left:auto;display:flex;gap:10px;align-items:center">
      <form class="searchbar" action="{{ route('admin.pendaftaran.index') }}" method="GET"><i class="fa-solid fa-magnifying-glass"></i><input name="q" value="{{ request('q') }}" placeholder="Cari santri / nomor..."></form>
      <a class="icon-top" href="{{ route('admin.pesan.index') }}" title="Inbox"><i class="fa-regular fa-bell"></i>@if(($nPesan ?? 0)>0)<span class="dot">{{ $nPesan }}</span>@endif</a>
      <button class="icon-top" id="themeToggle" title="Mode terang / gelap"><i class="fa-regular fa-moon" id="themeIcon"></i></button>
      <div class="dd" id="ddTambah">
        <button class="btn" type="button" onclick="document.getElementById('ddTambah').classList.toggle('open')"><i class="fa-solid fa-plus"></i> Tambah Baru ▾</button>
        <div class="dd-menu">
          <a href="{{ route('admin.berita.create') }}"><i class="fa-solid fa-newspaper"></i> Tulis Berita</a>
          <a href="{{ route('admin.program.create') }}"><i class="fa-solid fa-book-open"></i> Tambah Program</a>
          <a href="{{ route('admin.galeri.create') }}"><i class="fa-solid fa-image"></i> Upload Galeri</a>
          <a href="{{ route('admin.lembaga.create') }}"><i class="fa-solid fa-mosque"></i> Tambah Lembaga</a>
          <a href="{{ route('admin.organisasi.create') }}"><i class="fa-solid fa-sitemap"></i> Tambah Organisasi</a>
          <a href="{{ route('admin.pengasuh.create') }}"><i class="fa-solid fa-user"></i> Tambah Pengasuh</a>
          <a href="{{ route('admin.agenda.create') }}"><i class="fa-solid fa-calendar-days"></i> Tambah Agenda</a>
          <a href="{{ route('admin.users.create') }}"><i class="fa-solid fa-user-shield"></i> Buat Akun Admin</a>
        </div>
      </div>
    </div>
  </div>
  <div class="content">
    @if(session('sukses'))<div class="alert ok"><i class="fa-solid fa-circle-check"></i> {{ session('sukses') }}</div>@endif
    @if($errors->any())<div class="alert err"><i class="fa-solid fa-triangle-exclamation"></i> {{ $errors->first() }}</div>@endif
    @yield('content')
  </div>
</div>
<script>
document.addEventListener('click',e=>{const dd=document.getElementById('ddTambah');if(dd&&!dd.contains(e.target))dd.classList.remove('open');});
// Mode terang / gelap (tersimpan di browser)
(function(){
  const root=document.documentElement;
  const btn=document.getElementById('themeToggle');
  const applyIcon=()=>{const i=document.getElementById('themeIcon');if(i)i.className=root.dataset.theme==='dark'?'fa-regular fa-sun':'fa-regular fa-moon';};
  const syncChart=()=>{if(window.perfChart&&window.perfChart.options){const dark=root.dataset.theme==='dark';const g=dark?'#2b2854':'#eef0f7';const t=dark?'#9c96c9':'#8a8fa3';window.perfChart.options.scales.y.grid.color=g;window.perfChart.options.scales.y.ticks.color=t;window.perfChart.options.scales.x.ticks.color=t;window.perfChart.update();}};
  applyIcon(); syncChart();
  if(btn)btn.addEventListener('click',e=>{e.stopPropagation();const next=root.dataset.theme==='dark'?'light':'dark';root.dataset.theme=next;try{localStorage.setItem('kunuuzul-theme',next);}catch(_){}btn.classList.remove('theme-spin');void btn.offsetWidth;btn.classList.add('theme-spin');applyIcon();syncChart();});
})();
</script>
@yield('scripts')
</body>
</html>
