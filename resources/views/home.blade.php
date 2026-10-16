@extends('layouts.app')
@section('title', 'PP Kunuuzul Imam Kauman')

@if(false) {{-- hero dihapus, navigasi tetap di layout --}}
<!-- HERO : tanpa navbar sendiri, pakai navbar utama yang sama di semua halaman -->
<header id="hero" class="relative min-h-[85vh] text-white overflow-hidden bg-black select-none">
  <!-- Background layers (crossfade + ken burns) + poster statis biar tidak hitam sebelum JS jalan -->
  <div id="hero-bgs" class="absolute inset-0" style="background:url('/images/hero/slide-1.jpg') center/cover no-repeat;"></div>
  <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-black/40 to-black/70 pointer-events-none"></div>
  <!-- Garis waktu autoplay: di bawah, sangat tipis -->
  <div class="absolute bottom-0 left-0 right-0 h-px bg-white/10 z-30"><div id="hero-progress" class="h-full bg-red-600" style="width:0%"></div></div>

  <!-- Tengah hero — rata kiri mentok -->
  <div id="hero-stage" class="relative z-10 max-w-7xl mx-auto pl-1 pr-4 pt-28 md:pt-32 pb-44 text-left">
    <div class="inline-block text-[11px] font-bold bg-white/10 border border-white/20 px-3 py-1 rounded-full" id="hero-badge">📖 Tahfidz • Kitab • Formal</div>
    <div id="hero-live" class="hidden mt-2"><span class="inline-block text-[11px] font-bold bg-red-600 px-3 py-1 rounded-full animate-pulse">🔴 SIARAN LANGSUNG — dikontrol admin</span></div>
    <h1 id="hero-title" class="mt-4 text-4xl sm:text-5xl md:text-7xl font-extrabold leading-[1.08] min-h-[1.2em] text-balance">HAFAL QUR'AN,<br>SAMPAI TUJUAN.</h1>
    <p id="hero-sub" class="mt-4 text-sm md:text-base text-stone-200 max-w-2xl min-h-[3em]">Tahfidz 30 juz, kitab kuning, sekolah formal MTs-MA, dan pembinaan akhlak 24 jam untuk 500+ santri di Kauman.</p>
    <div class="mt-3 flex justify-start"><div class="h-[3px] w-44 flex"><div class="w-1/2 bg-blue-600"></div><div class="w-1/2 bg-red-600"></div></div></div>
    <div class="mt-7 flex flex-col sm:flex-row gap-3 justify-start">
      <a id="hero-cta1" href="{{ route('ppdb.form') }}" class="bg-red-600 hover:bg-red-500 hover:-translate-y-0.5 focus-visible:outline focus-visible:outline-2 focus-visible:outline-white transition font-bold text-xs tracking-widest px-8 py-4 shadow-lg shadow-red-900/40">DAFTAR PPDB & BROSUR</a>
      <a id="hero-cta2" href="{{ route('profil') }}" class="border border-white/60 hover:bg-white hover:text-black hover:-translate-y-0.5 focus-visible:outline focus-visible:outline-2 focus-visible:outline-white transition font-bold text-xs tracking-widest px-8 py-4">PROFIL PONDOK</a>
    </div>
  </div>

  <!-- Bawah hero: indikator pil interaktif -->
  <div class="absolute bottom-6 inset-x-0 z-10">
    <div class="max-w-3xl mx-auto px-4 flex justify-center">
      <div id="hero-segs" class="flex gap-2"></div>
    </div>
  </div>

<style>
#hero-bgs{position:absolute;inset:0;overflow:hidden;background:#0c0a09;}
#hero-bgs img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;object-position:center;opacity:0;transition:opacity 1.8s ease-in-out;will-change:transform,opacity;}
#hero-bgs img.active{opacity:1;}
/* 1 slide = 6 detik: zoom-in lalu zoom-out, tidak pernah di bawah 1.06 biar selalu penuh */
#hero-bgs img.kb-in.active{animation:kbInOut 6s ease-in-out forwards;}
#hero-bgs img.kb-out.active{animation:kbOutIn 6s ease-in-out forwards;}
@keyframes kbInOut{0%{transform:scale(1.05) translate(0,0);filter:brightness(.9);}30%{filter:brightness(1);}50%{transform:scale(1.12) translate(-1%,-.8%);}100%{transform:scale(1.05) translate(0,0);filter:brightness(1);}}
@keyframes kbOutIn{0%{transform:scale(1.12) translate(1%,.8%);filter:brightness(.9);}30%{filter:brightness(1);}50%{transform:scale(1.05) translate(0,0);}100%{transform:scale(1.12) translate(1%,.8%);filter:brightness(1);}}
#hero-bgs img.paused{animation-play-state:paused !important;}
#hero-title, #hero-sub{transition:opacity .35s ease, transform .35s ease;}
.hero-anim{opacity:0 !important;transform:translateY(18px) !important;}
@keyframes heroPop{0%{opacity:0;transform:translateY(22px) scale(.98)}100%{opacity:1;transform:none}}
.hero-pop{animation:heroPop .55s ease both;}
/* indikator minimalis: garis tipis, aktif terisi merah */
.seg{display:block;width:2.5rem;height:3px;background:rgba(255,255,255,.25);border-radius:9999px;overflow:hidden;transition:width .3s ease,background-color .3s ease,transform .2s ease;}
.seg:hover{background:rgba(255,255,255,.5);transform:scaleY(1.6);}
.seg.active{width:4rem;}
.seg-fill{display:block;height:100%;width:0;background:#ef4444;}
@media (prefers-reduced-motion: reduce){
  #hero-bgs img.kb-in.active, #hero-bgs img.kb-out.active{animation:none;transform:none;}
  .hero-pop{animation:none;}
}
</style>
</header>

<script>
@php
  // Slide dari admin (DB) — kalau kosong, pakai 4 bawaan agar tidak rusak.
  $dbSlides = isset($heroSlides) && $heroSlides->count() ? $heroSlides : collect();
@endphp
@if($dbSlides->count())
const heroSlides = [
@foreach($dbSlides as $i => $s)
  {num:'{{ str_pad($i+1,2,'0',STR_PAD_LEFT) }}', label:@json($s->label ?? 'PONDOK'), desc:@json($s->desc ?? ''), badge:@json($s->badge ?? ''), title:@json($s->title), sub:@json($s->sub ?? ''), img:@json($s->gambar ?: '/images/hero/slide-1.jpg'), cta1:[@json($s->cta1_text ?: 'DAFTAR PPDB'), @json($s->cta1_url ?: route('ppdb.form'))], cta2:[@json($s->cta2_text ?: 'PROFIL PONDOK'), @json($s->cta2_url ?: route('profil'))]},
@endforeach
];
@else
const heroSlides = [
  {num:'01', label:'TAHFIDZ QURAN', desc:'Setoran harian & sanad 30 juz', badge:'📖 Tahfidz • Sanad 30 Juz', title:"HAFAL QUR'AN,<br>SAMPAI TUJUAN.", sub:'Tahfidz 30 juz, tasmi harian, munaqasyah tiap semester. Target khatam bersanad.', img:'/images/hero/slide-1.jpg', cta1:['DAFTAR TAHFIDZ','{{ route('ppdb.form') }}'], cta2:['PROFIL PONDOK','{{ route('profil') }}']},
  {num:'02', label:'KITAB KUNING', desc:'Sorogan & bandongan intensif', badge:'📚 Diniyah • Sorogan Kitab', title:"ALIM KITAB,<br>AKHLAK MULIA.", sub:'Nahwu, Shorof, Fiqh, Tauhid, Tafsir. Kajian bandongan + sorogan setiap hari.', img:'/images/hero/slide-2.jpg', cta1:['LIHAT PROGRAM','{{ route('program.index') }}'], cta2:['KONTAK USTADZ','{{ route('kontak') }}']},
  {num:'03', label:'SEKOLAH FORMAL', desc:'MTs-MA + bilingual', badge:'🏫 MTs-MA • Bilingual', title:"ILMU DUNIA,<br>BEKAL AKHIRAT.", sub:'Ijazah Kemenag + bahasa Arab-Inggris intensif, IT, dan olimpiade.', img:'/images/hero/slide-3.jpg', cta1:['DAFTAR PPDB','{{ route('ppdb.form') }}'], cta2:['GALERI SANTRI','{{ route('galeri.index') }}']},
  {num:'04', label:'ASRAMA & TARBIYAH', desc:'Musyrif 24 jam • Ekstrakurikuler', badge:'🌙 Tarbiyah • Kemandirian', title:"BETAH MONDOK,<br>HATI TENANG.", sub:'Asrama nyaman, panahan, pramuka, kaligrafi, tahsin, dan klinik santri.', img:'/images/hero/slide-4.jpg', cta1:['DAFTAR SEKARANG','{{ route('ppdb.form') }}'], cta2:['BACA BERITA','{{ route('berita.index') }}']},
];
@endif
let heroIdx = -1, heroPlaying = true, heroDur = 6000, heroVirtual = Date.now(), heroLastT = performance.now();
const bgs = document.getElementById('hero-bgs'), prog = document.getElementById('hero-progress'), segs = document.getElementById('hero-segs');
prog.style.transition = 'none';

// pil indikator: klik untuk loncat, hover membesar, aktif mengembang + isi progress
heroSlides.forEach((s,i)=>{
  const b = document.createElement('button');
  b.className = 'seg' + (i===0 ? ' active' : '');
  b.title = s.num + ' • ' + s.label;
  b.setAttribute('aria-label', 'Ke slide ' + s.num + ' ' + s.label);
  b.innerHTML = `<span class="seg-fill"></span>`;
  b.onclick = (e)=>{ e.stopPropagation(); heroTo(i); };
  segs.appendChild(b);
});
function heroSegSync(){
  const pos = (heroVirtual%heroDur)/heroDur;
  [...segs.children].forEach((sg,i)=>{
    sg.classList.toggle('active', i===heroIdx);
    const f = sg.querySelector('.seg-fill');
    if(f) f.style.width = (i<heroIdx ? 100 : (i===heroIdx ? pos*100 : 0))+'%';
  });
}

// preload semua di awal (eager) biar tidak ada jeda hitam saat pindah slide
heroSlides.forEach((s,i)=>{
  const im = document.createElement('img'); im.src = s.img; im.alt = s.label; im.draggable = false;
  im.loading = 'eager';
  if(i === 0) im.fetchPriority = 'high';
  im.onerror = ()=>{ im.style.display = 'none'; };
  im.classList.add(i % 2 === 0 ? 'kb-in' : 'kb-out');
  if(i===0) im.classList.add('active'); bgs.appendChild(im);
});

function heroTextAnim(){
  const t = document.getElementById('hero-title'), s = document.getElementById('hero-sub');
  [t,s].forEach(el=>{ el.classList.add('hero-anim'); el.classList.remove('hero-pop'); });
  requestAnimationFrame(()=>requestAnimationFrame(()=>{
    const d = heroSlides[heroIdx];
    document.getElementById('hero-badge').textContent = d.badge;
    t.innerHTML = d.title; s.textContent = d.sub;
    const [t1,l1] = d.cta1, [t2,l2] = d.cta2;
    const c1 = document.getElementById('hero-cta1'), c2 = document.getElementById('hero-cta2');
    c1.textContent = t1; c1.href = l1; c2.textContent = t2; c2.href = l2;
    [t,s].forEach(el=>{ el.classList.remove('hero-anim'); el.classList.add('hero-pop'); });
  }));
}
function heroRender(){
  // tukar hanya kalau foto target sudah ke-load (tahan foto lama biar tidak blank)
  const target = bgs.children[heroIdx];
  const swap = ()=>{
    // bekukan foto lama tepat di frame zoom terakhirnya (anti snap/diam mendadak)
    const prev = bgs.querySelector('img.active');
    if(prev && prev !== target){
      const cs = getComputedStyle(prev);
      prev.style.transform = cs.transform;
      prev.style.filter = cs.filter;
      prev.classList.remove('active','paused');
      setTimeout(()=>{ prev.style.transform=''; prev.style.filter=''; }, 2000);
    }
    [...bgs.children].forEach((im)=>{ if(im !== prev) im.classList.remove('active','paused'); });
    void bgs.offsetWidth; // reflow sekali agar animasi foto baru mulai dari awal
    target.classList.add('active');
  };
  if(target.complete && target.naturalWidth){ swap(); }
  else {
    target.onload = swap;
    setTimeout(()=>{ if(!target.classList.contains('active') && target.naturalWidth) swap(); }, 2500);
  }
  heroTextAnim(); prog.style.width = '0%';
}
function heroTo(i){ const n = heroSlides.length; const cur = Math.floor(heroVirtual/heroDur)%n; const diff = ((i-cur)%n+n)%n; heroVirtual += diff*heroDur; heroIdx = -1; }
function heroGo(d){ heroVirtual += d*heroDur; heroIdx = -1; }
function heroPause(){
  if(!heroPlaying) return;
  heroPlaying = false;
  const cur = bgs.children[heroIdx]; if(cur) cur.classList.add('paused');
  const b = document.getElementById('hero-play'); if(b){ b.textContent='▶'; b.setAttribute('aria-label','Putar slider'); }
}
function heroResume(){
  heroPlaying = true;
  const b = document.getElementById('hero-play'); if(b){ b.textContent='⏸'; b.setAttribute('aria-label','Jeda slider'); }
  if(heroHeld){ heroHold(heroHeldIdx); } // tetap ikut siaran admin
  else { heroVirtual = Date.now(); heroIdx = -1; } // samakan ke jam live (realtime)
}
// mesin realtime: slider ikut jam dinding, bukan timer lokal yang mati saat pindah tab
function heroLoop(t){
  if(heroPlaying && !document.hidden){
    if(!heroHeld) heroVirtual += Math.min(t-heroLastT, 100); // saat ditahan admin: bekukan
    const idx = Math.floor(heroVirtual/heroDur)%heroSlides.length;
    if(idx !== heroIdx){ heroIdx = idx; heroRender(); }
    else { prog.style.width = ((heroVirtual%heroDur)/heroDur*100)+'%'; }
    heroSegSync();
  }
  heroLastT = t;
  window.__heroRAF = requestAnimationFrame(heroLoop);
}
// mode siaran admin: tahan 1 slide di semua layar, atau lepas kembali otomatis
let heroHeld = false, heroHeldIdx = 0;
function heroHold(i){
  heroHeld = true; heroHeldIdx = i;
  const n = heroSlides.length;
  const base = Math.ceil(Date.now()/heroDur)*heroDur;
  const diff = ((i - (base/heroDur % n))%n+n)%n;
  heroVirtual = base + diff*heroDur; heroIdx = -1;
  document.getElementById('hero-live')?.classList.remove('hidden');
}
function heroRelease(){
  heroHeld = false; heroVirtual = Date.now(); heroIdx = -1;
  document.getElementById('hero-live')?.classList.add('hidden');
}
// pindah tab tidak pause lagi: saat kembali langsung lompat ke slide live
document.addEventListener('visibilitychange', ()=>{ if(!document.hidden && heroPlaying){ heroVirtual = Date.now(); heroIdx = -1; } });
const playBtn = document.getElementById('hero-play');
if(playBtn){ playBtn.onclick = (e)=>{ e.stopPropagation(); heroPlaying ? heroPause() : heroResume(); }; }

// tidak ada jeda saat cursor di gambar — autoplay terus jalan
const stage = document.getElementById('hero-stage'), hero = document.getElementById('hero');

// keyboard
document.addEventListener('keydown', (e)=>{
  if(e.key==='ArrowRight') heroGo(1);
  else if(e.key==='ArrowLeft') heroGo(-1);
  else if(e.code==='Space' && window.scrollY < window.innerHeight){ e.preventDefault(); heroPlaying ? heroPause() : heroResume(); }
});

// drag / swipe (mouse + touch)
let dragX=null;
stage.addEventListener('pointerdown', e=>{ dragX=e.clientX; try{stage.setPointerCapture(e.pointerId);}catch(_){} });
stage.addEventListener('pointerup', e=>{
  if(dragX===null) return;
  const dx=e.clientX-dragX;
  if(Math.abs(dx)>50) heroGo(dx<0?1:-1);
  dragX=null;
});
let touchX=null;
hero.addEventListener('touchstart', e=>{ touchX=e.touches[0].clientX; }, {passive:true});
hero.addEventListener('touchend', e=>{
  if(touchX===null) return;
  const dx=e.changedTouches[0].clientX-touchX;
  if(Math.abs(dx)>50) heroGo(dx<0?1:-1);
  touchX=null;
}, {passive:true});

if(window.__heroRAF) cancelAnimationFrame(window.__heroRAF);
window.__heroRAF = requestAnimationFrame(heroLoop);
</script>

<script type="module">
// Realtime via Laravel Reverb: dengarkan siaran slide dari admin.
// Opsional — kalau server Reverb mati/offline, slider otomatis lokal tetap jalan.
try {
  const [{ default: Echo }, { default: Pusher }] = await Promise.all([
    import('https://esm.sh/laravel-echo@1.19.0'),
    import('https://esm.sh/pusher-js@8.4.0'),
  ]);
  window.Pusher = Pusher;
  const echo = new Echo({
    broadcaster: 'reverb',
    key: '{{ config('broadcasting.connections.reverb.key') }}',
    wsHost: window.location.hostname,
    wsPort: {{ (int) config('broadcasting.connections.reverb.options.port', 8080) }},
    wssPort: {{ (int) config('broadcasting.connections.reverb.options.port', 8080) }},
    forceTLS: false,
    enabledTransports: ['ws', 'wss'],
  });
  echo.channel('pondok-hero').listen('.slide.changed', (e) => {
    if(e.mode === 'tahan'){ heroHold(e.index); }
    else { heroRelease(); }
  });
} catch(err) { console.info('Realtime nonaktif, slider otomatis lokal.', err); }
</script>
@endif

@section('content')

<!-- ============ SOROTAN KAUMAN : model orisinal ============ -->
@php
  $heroImgs = ['/images/hero/slide-1.jpg','/images/hero/slide-2.jpg','/images/hero/slide-3.jpg','/images/hero/slide-4.jpg'];
  $b0 = ($sorotan ?? collect())->first();
  // Foto berita dari admin, fallback ke foto bawaan bila belum diupload.
  $gambarBerita = function($m, $i = 0) use ($heroImgs) {
    if ($m && !empty($m->gambar)) {
      $g = $m->gambar;
      if (str_starts_with($g, '/') || str_starts_with($g, 'http')) return $g;
      return '/storage/'.$g;
    }
    return $heroImgs[$i % 4];
  };
  $bulanId = [1=>'Jan',2=>'Feb',3=>'Mar',4=>'Apr',5=>'Mei',6=>'Jun',7=>'Jul',8=>'Agu',9=>'Sep',10=>'Okt',11=>'Nov',12=>'Des'];
@endphp
<section class="relative bg-stone-50 overflow-hidden border-b border-stone-100">
<div class="absolute inset-0 opacity-[0.06] pointer-events-none" style="background-image:radial-gradient(#065f46 1.2px,transparent 1.2px);background-size:22px 22px;"></div>
<div class="relative max-w-7xl mx-auto px-4 pt-8 md:pt-10 pb-12 md:pb-14">

  <!-- Bento editorial -->
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mt-5 items-stretch">
    @php
      $sorotanSlides = ($sorotan ?? collect())->values();
      $sorotanJson = $sorotanSlides->map(function($s){
        return [
          'judul' => $s->judul,
          'excerpt' => $s->excerpt,
          'kat' => strtoupper($s->kategori ?? 'Pondok'),
          'tgl' => ($s->published_at ? $s->published_at->format('d M Y') : 'Terbaru') . ' • 4 mnt baca',
          'url' => route('berita.show', $s->slug),
        ];
      })->values()->toJson();
    @endphp
    <div id="sorotan-card" class="reveal lg:col-span-2 relative rounded-[1.6rem] overflow-hidden group min-h-[400px] lg:min-h-[500px] flex shadow-xl shadow-emerald-900/10">
      <div id="sorotan-imgs" class="absolute inset-0 bg-emerald-950">
        @foreach($sorotanSlides as $i => $s)
        @php
          $srcSorot = $gambarBerita($s, $i);
          // Versi WebP (~45% lebih ringan) untuk gambar bawaan; foto upload tetap pakai file asli.
          $webpSorot = (str_starts_with($srcSorot, '/images/hero/') && str_ends_with($srcSorot, '.jpg')) ? substr($srcSorot, 0, -4).'.webp' : null;
        @endphp
        <picture>
          @if($webpSorot)<source srcset="{{ $webpSorot }}" type="image/webp">@endif
          <img src="{{ $srcSorot }}" data-i="{{ $i }}" loading="eager" fetchpriority="{{ $i===0 ? 'high' : 'auto' }}" decoding="async" class="sorotan-img{{ $i===0 ? ' active' : '' }} {{ $i%2===0 ? 'kb-in' : 'kb-out' }}" alt="{{ $s->judul }}">
        </picture>
        @endforeach
      </div>
      <div class="absolute inset-0 bg-gradient-to-t from-emerald-950/95 via-emerald-950/30 to-emerald-950/5 pointer-events-none"></div>
      <div class="absolute top-5 left-5 right-5 flex items-center gap-2 flex-wrap">
        <span class="text-[11px] font-extrabold bg-amber-400 text-emerald-950 px-3 py-1.5 rounded-full">★ SOROTAN UTAMA</span>
        <span id="sorotan-kat" class="text-[11px] font-bold bg-white/15 backdrop-blur text-white px-3 py-1.5 rounded-full border border-white/20">{{ strtoupper($b0->kategori ?? 'Pondok') }}</span>
        @if($sorotanSlides->count() > 1)
        <span class="ml-auto flex items-center gap-1.5" id="sorotan-dots">
          @foreach($sorotanSlides as $i => $s)
          <button data-dot="{{ $i }}" aria-label="Slide {{ $i+1 }}" class="sor-dot{{ $i===0 ? ' active' : '' }}"></button>
          @endforeach
        </span>
        @endif
      </div>
      <div class="relative mt-auto p-6 md:p-8 text-white w-full">
        <a id="sorotan-link" href="{{ $b0 ? route('berita.show',$b0->slug) : route('berita.index') }}"><h2 id="sorotan-judul" class="font-extrabold text-[22px] md:text-[2rem] leading-[1.15] max-w-2xl text-balance">{{ $b0->judul ?? 'Membentuk Generasi Qurani: Ikhtiar Baru Kunuuzul Imam Kauman' }}</h2></a>
        <p id="sorotan-excerpt" class="text-emerald-100/90 text-[14px] mt-3 max-w-xl line-clamp-2">{{ $b0->excerpt ?? 'Dari tahfidz bersanad, sorogan kitab, hingga pembinaan akhlak 24 jam — inilah wajah baru pendidikan pesantren.' }}</p>
        <div class="flex items-center gap-3 mt-5 pt-5 border-t border-white/15">
          <div class="w-10 h-10 rounded-full bg-amber-400 text-emerald-950 flex items-center justify-center font-extrabold shrink-0">ك</div>
          <div class="min-w-0"><p class="text-[13px] font-bold">Redaksi Kauman</p><p id="sorotan-tgl" class="text-[11px] text-emerald-200">{{ $b0 && $b0->published_at ? $b0->published_at->format('d M Y') : 'Terbaru' }} • 4 mnt baca</p></div>
          <a id="sorotan-btn" href="{{ $b0 ? route('berita.show',$b0->slug) : route('berita.index') }}" class="ml-auto bg-white text-emerald-950 text-[12px] font-extrabold px-5 py-2.5 rounded-full group-hover:bg-amber-400 hover:bg-amber-400 transition shrink-0">Baca →</a>
        </div>
      </div>
    </div>
    <div class="flex flex-col gap-5 h-full">
      <div class="reveal lift bg-emerald-950 text-white rounded-[1.6rem] p-6 relative overflow-hidden shrink-0">
        <p class="font-arab text-[26px] leading-snug text-amber-400 quote-glow">{{ \App\Models\Setting::get('dawuh_arab', '﴿ خَيْرُكُمْ مَنْ تَعَلَّمَ الْقُرْآنَ وَعَلَّمَهُ ﴾') }}</p>
        <p class="text-[13px] text-emerald-100 mt-3 leading-relaxed italic">{{ \App\Models\Setting::get('dawuh_terjemah', '“Sebaik-baik kalian adalah yang belajar Al-Qur’an dan mengajarkannya.”') }}</p>
        <p class="text-[11px] font-bold text-amber-400 mt-3 tracking-widest">{{ \App\Models\Setting::get('dawuh_sumber', '— HR. BUKHARI • DAWUH PENGASUH') }}</p>
        <div class="absolute -right-8 -bottom-8 w-36 h-36 rounded-full bg-amber-400/15 pointer-events-none float-slow"></div>
      </div>
      <div class="reveal bg-white rounded-[1.6rem] border border-stone-100 p-6 shadow-sm flex-1 flex flex-col">
        <div class="flex items-center justify-between"><p class="text-[11px] font-extrabold tracking-[0.15em] text-emerald-700">● LINIMASA 24 JAM</p><a href="{{ route('berita.index') }}" class="text-[11px] font-bold text-stone-400 hover:text-emerald-700">Lihat →</a></div>
        <div class="mt-2 flex flex-col divide-y divide-stone-100">
          @forelse(($linimasa ?? collect()) as $tl)
          <a href="{{ route('berita.show',$tl->slug) }}" class="relative py-3.5 pl-5 group"><span class="absolute left-0 top-5 w-2.5 h-2.5 rounded-full bg-amber-400 ring-4 ring-amber-100"></span><p class="text-[13.5px] font-bold leading-snug group-hover:text-emerald-700 line-clamp-2">{{ $tl->judul }}</p><p class="text-[11px] text-stone-400 mt-1">{{ $tl->published_at ? $tl->published_at->diffForHumans() : '' }} • Kabar</p></a>
          @empty
          <p class="text-[13px] text-stone-500">Belum ada kabar.</p>
          @endforelse
        </div>
      </div>
    </div>
  </div>

<style>
.sorotan-img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;opacity:0;transition:opacity 1.5s ease;will-change:transform;}
.sorotan-img.active{opacity:1;}
.sorotan-img.kb-in.active{animation:sorIn 16s ease-in-out infinite;}
.sorotan-img.kb-out.active{animation:sorOut 16s ease-in-out infinite;}
@keyframes sorIn{0%{transform:scale(1.05)}50%{transform:scale(1.16)}100%{transform:scale(1.05)}}
@keyframes sorOut{0%{transform:scale(1.16)}50%{transform:scale(1.05)}100%{transform:scale(1.16)}}
#sorotan-judul,#sorotan-excerpt{transition:opacity .35s ease;}
.sor-fade{opacity:0;}
.sor-dot{width:8px;height:8px;border-radius:9999px;background:rgba(255,255,255,.4);transition:all .3s ease;padding:0;border:0;cursor:pointer;}
.sor-dot.active{width:22px;background:#fbbf24;}
@media (prefers-reduced-motion: reduce){.sorotan-img.kb-in.active,.sorotan-img.kb-out.active{animation:none;}}
</style>
<script>
(function(){
  if(window.__sorTimer){ try{clearTimeout(window.__sorTimer);}catch(_){} try{clearInterval(window.__sorTimer);}catch(_){} window.__sorTimer=null; }
  const slides = {!! $sorotanJson !!};
  const card = document.getElementById('sorotan-card');
  const imgs = [...document.querySelectorAll('#sorotan-imgs .sorotan-img')];
  if(!slides || slides.length < 2){
    // Mode 1 berita: pastikan zoom lambat jalan terus (infinite via CSS),
    // restart sekali biar tidak berhenti di frame akhir cache lama.
    const one = document.querySelector('#sorotan-imgs .sorotan-img');
    if(one){
      one.classList.add('active');
      if(!one.classList.contains('kb-in') && !one.classList.contains('kb-out')) one.classList.add('kb-in');
      void one.offsetWidth;
    }
    return;
  }
  const dots = [...document.querySelectorAll('#sorotan-dots .sor-dot')];
  const judul = document.getElementById('sorotan-judul');
  const excerpt = document.getElementById('sorotan-excerpt');
  const kat = document.getElementById('sorotan-kat');
  const tgl = document.getElementById('sorotan-tgl');
  const link = document.getElementById('sorotan-link');
  const btn = document.getElementById('sorotan-btn');
  const heroFb = {!! json_encode($heroImgs) !!};
  // Gambar slide berikutnya sering belum selesai diunduh saat waktunya ganti
  // (foto upload 1-2MB dari server jauh) → crossfade menampilkan latar gelap sesaat.
  // Solusi: unduh semua di awal + tahan gambar lama sampai gambar baru siap.
  const imgReady = (im)=>{ try{ return im.complete && im.naturalWidth > 0; }catch(_){ return false; } };
  function whenReady(im, cb){
    if(imgReady(im)){ cb(); return; }
    let done = false;
    const fin = ()=>{ if(!done){ done = true; cb(); } };
    const to = setTimeout(fin, 1000); // jangan macet selamanya kalau jaringan lelet
    im.addEventListener('load', ()=>{ clearTimeout(to); fin(); }, {once:true});
    im.addEventListener('error', ()=>{ clearTimeout(to); fin(); }, {once:true});
  }
  imgs.forEach((im,i)=>{
    // Fallback: kalau file /storage hilang (mis. redeploy tanpa volume),
    // ganti ke gambar bawaan agar tidak ada lubang gelap.
    im.addEventListener('error', function h(){
      const fb = heroFb[i % heroFb.length];
      if(im.getAttribute('src') !== fb){ im.src = fb; }
      else { im.removeEventListener('error', h); }
    });
    // Picu unduhan sejak awal (abaikan sisa atribut lazy bila ada di cache lama)
    try{ if(im.loading && im.loading !== 'eager'){ im.loading = 'eager'; } }catch(_){}
  });
  let idx = 0, paused = false, transId = 0;
  const DUR = 5000;
  // Jadwal berbasis jam dinding: posisi slide dihitung dari waktu nyata,
  // bukan hitungan manual — tahan banting saat tab disembunyikan / browser menjeda timer.
  let t0 = Date.now(), lastTick = Date.now();
  function stop(){ if(window.__sorTimer){ clearTimeout(window.__sorTimer); clearInterval(window.__sorTimer); window.__sorTimer = null; } }
  function go(n){
    idx = ((n % slides.length) + slides.length) % slides.length;
    const my = ++transId; // batalkan bersih-bersih transisi lama kalau disalip jadwal baru
    const d = slides[idx];
    const next = imgs[idx];
    dots.forEach((dt,i)=>dt.classList.toggle('active', i===idx));
    const swap = ()=>{
      if(my !== transId) return;
      const prev = imgs.find(im=>im.classList.contains('active'));
      if(prev && prev !== next){
        // Bekukan frame zoom terakhir gambar lama biar tidak snap saat transisi
        try{
          const cs = getComputedStyle(prev);
          if(cs.transform && cs.transform !== 'none') prev.style.transform = cs.transform;
        }catch(_){}
        // Naikkan gambar baru ke atas, fade-in dulu (crossfade), baru gambar lama dilepas
        next.style.zIndex = '2'; prev.style.zIndex = '1';
        next.classList.remove('kb-in','kb-out');
        void next.offsetWidth;
        next.classList.add('active', idx%2===0 ? 'kb-in' : 'kb-out');
        setTimeout(()=>{
          if(my !== transId) return;
          prev.classList.remove('active');
          setTimeout(()=>{ if(my !== transId) return; prev.classList.remove('kb-in','kb-out'); prev.style.transform=''; prev.style.zIndex=''; next.style.zIndex=''; }, 1600);
        }, 200);
      } else if(!prev){
        next.classList.add('active', idx%2===0 ? 'kb-in' : 'kb-out');
      }
    };
    // Tukar gambar hanya kalau sudah siap tampil; kalau belum, gambar lama
    // tetap jalan (tidak ada jeda gelap), swap menyusul begitu unduhan tiba.
    if(imgReady(next)){ swap(); }
    else { whenReady(next, swap); }
    const applyText = ()=>{
      if(my !== transId) return;
      judul.textContent = d.judul; excerpt.textContent = d.excerpt ?? '';
      kat.textContent = d.kat; tgl.textContent = d.tgl;
      link.href = d.url; btn.href = d.url;
      [judul,excerpt].forEach(el=>el.classList.remove('sor-fade'));
    };
    [judul,excerpt].forEach(el=>el.classList.add('sor-fade'));
    setTimeout(applyText, 320);
  }
  function retime(){ t0 = Date.now() - idx*DUR; lastTick = Date.now(); }
  function tick(){
    const now = Date.now();
    // Saat hover / tab tidak aktif: bekukan jadwal (geser acuan waktu), jangan kejar tayang.
    if(paused || document.hidden){ t0 += now - lastTick; lastTick = now; return; }
    lastTick = now;
    const should = Math.floor((now - t0)/DUR) % slides.length;
    if(should !== idx) go(should);
  }
  dots.forEach(dt=>dt.addEventListener('click',(e)=>{e.preventDefault();e.stopPropagation();go(+dt.dataset.dot);retime();}));
  function syncOnReturn(){
    if(document.hidden) return;
    tick(); retime(); // lanjut mulus dari slide aktif, tanpa lompat
  }
  function onSorHide(){ stop(); }
  document.addEventListener('visibilitychange', syncOnReturn);
  window.addEventListener('focus', syncOnReturn);
  window.addEventListener('pageshow', syncOnReturn);
  card.addEventListener('mouseenter',()=>paused=true);
  card.addEventListener('mouseleave',()=>{paused=false; retime();});
  document.addEventListener('turbo:before-render',()=>{ onSorHide(); document.removeEventListener('visibilitychange', syncOnReturn); window.removeEventListener('focus', syncOnReturn); window.removeEventListener('pageshow', syncOnReturn); }, {once:true});
  stop();
  window.__sorTimer = setInterval(tick, 500);
})();
</script>

  <!-- Kabar + Majelis -->
  <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 mt-8 md:mt-10 items-start">
    <div class="lg:col-span-8 min-w-0">
      <div class="flex items-end justify-between gap-4 flex-wrap">
        <div>
          <p class="text-[11px] font-extrabold tracking-[0.2em] text-amber-600">KABAR • KAJIAN • PRESTASI</p>
          <h2 class="text-[24px] md:text-[26px] font-extrabold text-emerald-950 leading-tight mt-1">Cerita dari Kauman</h2>
          <div class="h-[3px] w-14 bg-amber-400 rounded-full mt-2"></div>
        </div>
        <a href="{{ route('berita.index') }}" class="text-[12px] font-extrabold bg-emerald-950 text-white px-5 py-2.5 rounded-full hover:bg-emerald-800 transition shrink-0">Indeks →</a>
      </div>

      <div class="grid sm:grid-cols-2 gap-5 mt-6 items-stretch">
        @if(($cerita ?? collect())->count())
        @forelse($cerita as $i => $lb)
        <a href="{{ route('berita.show',$lb->slug) }}" class="reveal bg-white rounded-[1.4rem] overflow-hidden border border-stone-100 shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 group flex flex-col">
          <div class="relative h-48 overflow-hidden shrink-0">
            <img src="{{ $gambarBerita($lb, $i) }}" loading="lazy" decoding="async" class="w-full h-full object-cover group-hover:scale-105 transition duration-700" alt="{{ $lb->judul }}">
            <span class="absolute bottom-3 left-3 text-[11px] font-extrabold bg-emerald-950/90 backdrop-blur text-amber-300 px-3 py-1.5 rounded-full">{{ strtoupper($lb->kategori ?? 'Kabar') }}</span>
            <span class="absolute top-3 right-4 text-[20px] font-extrabold text-white/90 drop-shadow">0{{ $i+1 }}</span>
          </div>
          <div class="p-5 flex flex-col flex-1">
            <h3 class="font-extrabold text-[15.5px] leading-snug group-hover:text-emerald-800 line-clamp-2 min-h-[3.2em]">{{ $lb->judul }}</h3>
            <p class="text-[13px] text-stone-500 mt-2 line-clamp-2">{{ $lb->excerpt }}</p>
            <div class="flex items-center gap-2 mt-auto pt-4 text-[11px] text-stone-400 font-bold"><span>{{ $lb->published_at ? $lb->published_at->format('d M Y') : 'Baru' }}</span><span>•</span><span>4 mnt</span><span class="ml-auto text-emerald-700">Baca →</span></div>
          </div>
        </a>
        @empty
        @endforelse
        @endif
      </div>

    </div>

    <aside class="lg:col-span-4 min-w-0">
      <div class="lg:sticky lg:top-24 flex flex-col gap-5">
      <div class="reveal bg-white rounded-[1.4rem] border border-stone-100 p-6 shadow-sm">
        <div class="flex items-center justify-between gap-2"><h3 class="font-extrabold text-emerald-950 text-[15px]">Majelis Terdekat</h3><span class="text-[11px] font-bold bg-amber-100 text-amber-700 px-3 py-1 rounded-full shrink-0">{{ ($agendas ?? collect())->count() }} Agenda</span></div>
        <div class="mt-4 flex flex-col gap-3">
          @forelse(($agendas ?? collect()) as $jd)
          <div class="flex items-center gap-3 bg-stone-50 border border-stone-100 rounded-2xl p-3 hover:border-emerald-300 hover:bg-emerald-50/50 transition">
            <div class="w-12 h-12 rounded-xl bg-emerald-950 text-white flex flex-col items-center justify-center shrink-0"><span class="text-[15px] font-extrabold leading-none">{{ $jd->tanggal->format('d') }}</span><span class="text-[10px] font-bold text-amber-300">{{ $bulanId[$jd->tanggal->month] ?? '' }}</span></div>
            <p class="text-[13px] font-bold leading-snug">{{ $jd->judul }}{{ $jd->tempat ? ' • '.$jd->tempat : '' }}</p>
          </div>
          @empty
          @foreach([['22','Sep','Sorogan Fathul Qorib • Ba’da Ashar'],['26','Sep','Tasmi Akbar Tahfidz • Aula'],['03','Okt','Haflah & Wisuda Juz • Lapangan']] as $jd)
          <div class="flex items-center gap-3 bg-stone-50 border border-stone-100 rounded-2xl p-3 hover:border-emerald-300 hover:bg-emerald-50/50 transition">
            <div class="w-12 h-12 rounded-xl bg-emerald-950 text-white flex flex-col items-center justify-center shrink-0"><span class="text-[15px] font-extrabold leading-none">{{ $jd[0] }}</span><span class="text-[10px] font-bold text-amber-300">{{ $jd[1] }}</span></div>
            <p class="text-[13px] font-bold leading-snug">{{ $jd[2] }}</p>
          </div>
          @endforeach
          @endforelse
        </div>
      </div>
      <div class="reveal bg-white rounded-[1.4rem] border border-stone-100 p-6 shadow-sm">
        <h3 class="font-extrabold text-emerald-950 text-[15px]">Jelajahi Topik</h3>
        <div class="flex flex-wrap gap-2 mt-3">
          @if(($topiks ?? collect())->count())
          @foreach($topiks as $tp)
          <a href="{{ route('berita.index', ['kategori' => $tp]) }}" class="text-[12px] font-bold px-3.5 py-1.5 rounded-full border border-stone-200 hover:bg-emerald-950 hover:text-white hover:border-emerald-950 transition">#{{ \Str::headline($tp) }}</a>
          @endforeach
          @else
          @foreach(['Tahfidz','Kitab Kuning','Prestasi','PPDB','Kajian','Khidmah','Bahasa','Asrama'] as $tp)
          <a href="{{ route('berita.index') }}" class="text-[12px] font-bold px-3.5 py-1.5 rounded-full border border-stone-200 hover:bg-emerald-950 hover:text-white hover:border-emerald-950 transition">#{{ $tp }}</a>
          @endforeach
          @endif
        </div>
        @if(($palingdibaca ?? collect())->count())
        <div class="mt-5 pt-5 border-t border-stone-100">
          <p class="text-[11px] font-extrabold tracking-[0.15em] text-stone-400">PALING DIBACA</p>
          <div class="mt-1 flex flex-col">
          @foreach($palingdibaca as $idx=>$pp)
            <a href="{{ route('berita.show',$pp->slug) }}" class="py-3 flex gap-3 items-start group border-b border-stone-100 last:border-0"><span class="text-[26px] leading-none font-extrabold text-transparent shrink-0" style="-webkit-text-stroke:1.5px #065f46;">{{ $idx+1 }}</span><span class="min-w-0"><span class="text-[13px] font-bold leading-snug group-hover:text-emerald-700 line-clamp-2 block">{{ $pp->judul }}</span><span class="text-[11px] text-stone-400 font-bold mt-1 block">👁 {{ number_format($pp->views ?? 0) }} dibaca</span></span></a>
          @endforeach
          </div>
        </div>
        @endif
      </div>
      </div>
    </aside>
  </div>

  <div class="mt-8 md:mt-10">
    <div class="flex items-end justify-between gap-4 flex-wrap">
      <div><p class="text-[11px] font-extrabold tracking-[0.2em] text-amber-600">INFO RESMI</p><h3 class="text-[20px] font-extrabold text-emerald-950 mt-1">Maklumat Pondok</h3></div>
      <a href="{{ route('berita.index') }}" class="text-[12px] font-bold text-stone-500 hover:text-emerald-700">Lihat semua →</a>
    </div>
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5 mt-5 items-stretch">
    @if(($maklumats ?? collect())->count())
    @foreach($maklumats as $i => $pg)
    <a href="{{ route('berita.show', $pg->slug) }}" class="reveal bg-white rounded-[1.4rem] border border-stone-100 p-5 flex gap-4 hover:shadow-lg hover:-translate-y-1 transition group h-full">
      <div class="w-16 h-16 rounded-2xl overflow-hidden shrink-0"><img src="{{ $gambarBerita($pg, $i) }}" loading="lazy" decoding="async" alt="{{ $pg->judul }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500"></div>
      <div class="min-w-0"><span class="text-[10px] font-extrabold tracking-[0.15em] text-amber-600">● MAKLUMAT</span><h4 class="font-extrabold text-[15px] leading-snug mt-1">{{ $pg->judul }}</h4><p class="text-[13px] text-stone-500 mt-1 line-clamp-2">{{ $pg->excerpt }}</p></div>
    </a>
    @endforeach
    @else
    @foreach([['Daurah Ilmiyyah','Kajian Tarikh al-Hawadits bersama masyayikh — terbuka santri & umum.',1],['Muhadhoroh Ammah','Nashaih al-Jailani: menata adab sebelum ilmu.',0],['Wali Santri Baru','Masa ta’aruf tahap II — mohon dampingi putra-putri.',2]] as $pg)
    <div class="reveal bg-white rounded-[1.4rem] border border-stone-100 p-5 flex gap-4 hover:shadow-lg hover:-translate-y-1 transition group h-full">
      <div class="w-16 h-16 rounded-2xl overflow-hidden shrink-0"><img src="{{ $heroImgs[$pg[2]%4] }}" loading="lazy" decoding="async" alt="{{ $pg[0] }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500"></div>
      <div class="min-w-0"><span class="text-[10px] font-extrabold tracking-[0.15em] text-amber-600">● MAKLUMAT</span><h4 class="font-extrabold text-[15px] leading-snug mt-1">{{ $pg[0] }}</h4><p class="text-[13px] text-stone-500 mt-1 line-clamp-2">{{ $pg[1] }}</p></div>
    </div>
    @endforeach
    @endif
    </div>
  </div>

</div>
<style>
.line-clamp-2{display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
</style>
</section>
<!-- ============ /SOROTAN KAUMAN ============ -->

<!-- Berita ala portal pesantren : besar + bulat + sidebar -->
<section class="bg-[#f4f6f5] border-t border-stone-100">
<div class="max-w-7xl mx-auto px-4 py-12 md:py-14">
  <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
    <!-- kiri -->
    <div class="lg:col-span-8 min-w-0">
      <div class="flex items-center justify-between gap-3">
        <h2 class="text-[20px] font-extrabold text-stone-900">Berita</h2>
        <div class="flex gap-1 text-[12px] font-bold bg-white rounded-full p-1 border" role="tablist">
          <button id="tab-recent" onclick="switchBeritaTab('recent')" class="bg-emerald-500 text-white px-4 py-1.5 rounded-full transition">Recent</button>
          @if(($popularList ?? collect())->count())<button id="tab-popular" onclick="switchBeritaTab('popular')" class="px-4 py-1.5 rounded-full text-stone-500 hover:text-emerald-600 transition">Popular</button>@endif
        </div>
      </div>
      @php
        $recentList = ($recentList ?? collect())->values();
        $popularList = ($popularList ?? collect())->values();
        $bnR = $recentList->first(); $bnP = $popularList->first();
      @endphp
      <div class="grid md:grid-cols-2 gap-5 mt-4 items-stretch">
        <div class="relative min-h-[380px] md:min-h-[480px]">
          <a id="berita-big-recent" href="{{ $bnR ? route('berita.show',$bnR->slug) : route('berita.index') }}" class="reveal absolute inset-0 rounded-2xl overflow-hidden group flex shadow-sm">
            <img src="{{ $gambarBerita($bnR, 0) }}" loading="lazy" decoding="async" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition duration-700" alt="{{ $bnR->judul ?? 'Berita terbaru' }}">
            <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/20 to-transparent"></div>
            <div class="relative mt-auto p-5 text-white">
              <span class="text-[11px] font-bold tracking-wider text-white/90">BERITA • TERBARU</span>
              <h3 class="font-extrabold text-[19px] leading-snug mt-1">{{ $bnR->judul ?? 'Kuliah Syariah Hadirkan Dua Narasumber' }}</h3>
              <p class="text-[11px] text-white/70 mt-3 font-semibold">REDAKSI KAUMAN • {{ $bnR && $bnR->published_at ? $bnR->published_at->format('d F Y') : '21 SEPTEMBER 2026' }}</p>
            </div>
          </a>
          <a id="berita-big-popular" href="{{ $bnP ? route('berita.show',$bnP->slug) : route('berita.index') }}" class="absolute inset-0 rounded-2xl overflow-hidden group hidden shadow-sm" style="display:none">
            <img src="{{ $gambarBerita($bnP, 2) }}" loading="lazy" decoding="async" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition duration-700" alt="{{ $bnP->judul ?? 'Berita populer' }}">
            <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/20 to-transparent"></div>
            <div class="relative mt-auto p-5 text-white">
              <span class="text-[11px] font-bold tracking-wider text-amber-300">★ POPULER</span>
              <h3 class="font-extrabold text-[19px] leading-snug mt-1">{{ $bnP->judul ?? 'Paling banyak dibaca' }}</h3>
              <p class="text-[11px] text-white/70 mt-3 font-semibold">REDAKSI KAUMAN • {{ $bnP && $bnP->published_at ? $bnP->published_at->format('d F Y') : '' }} • 🔥 Populer</p>
            </div>
          </a>
        </div>
        <div class="bg-white rounded-2xl border border-stone-100 p-4 flex flex-col justify-center gap-5 shadow-sm">
          <div id="berita-list-recent" class="flex flex-col gap-5">
            @forelse($recentList->skip(1)->values() as $i => $bb)
            <a href="{{ route('berita.show',$bb->slug) }}" class="flex gap-4 group items-start">
              <img src="{{ $gambarBerita($bb, $i+1) }}" loading="lazy" decoding="async" class="w-[88px] h-[88px] rounded-full object-cover shrink-0" alt="{{ $bb->judul }}">
              <div class="min-w-0"><p class="text-[11px] font-bold text-emerald-500 tracking-wider">{{ strtoupper($bb->kategori ?? 'BERITA') }}</p><h4 class="font-bold text-[14.5px] leading-snug mt-1 line-clamp-3 group-hover:text-emerald-700">{{ $bb->judul }}</h4></div>
            </a>
            @empty
            @endforelse
          </div>
          <div id="berita-list-popular" class="flex-col gap-5" style="display:none">
            @forelse($popularList->skip(1)->values() as $i => $bb)
            <a href="{{ route('berita.show',$bb->slug) }}" class="flex gap-4 group items-start">
              <img src="{{ $gambarBerita($bb, $i+2) }}" loading="lazy" decoding="async" class="w-[88px] h-[88px] rounded-full object-cover shrink-0 ring-2 ring-amber-200" alt="{{ $bb->judul }}">
              <div class="min-w-0"><p class="text-[11px] font-bold text-amber-600 tracking-wider">★ {{ strtoupper($bb->kategori ?? 'BERITA') }}</p><h4 class="font-bold text-[14.5px] leading-snug mt-1 line-clamp-3 group-hover:text-emerald-700">{{ $bb->judul }}</h4></div>
            </a>
            @empty
            @endforelse
          </div>
        </div>
      </div>
      <script>
      function switchBeritaTab(mode){
        const isR = mode === 'recent';
        document.getElementById('berita-list-recent').style.display = isR ? 'flex' : 'none';
        document.getElementById('berita-list-popular').style.display = isR ? 'none' : 'flex';
        document.getElementById('berita-big-recent').style.display = isR ? 'flex' : 'none';
        document.getElementById('berita-big-popular').style.display = isR ? 'none' : 'flex';
        const tr = document.getElementById('tab-recent'), tp = document.getElementById('tab-popular');
        tr.className = isR ? 'bg-emerald-500 text-white px-4 py-1.5 rounded-full transition' : 'px-4 py-1.5 rounded-full text-stone-500 hover:text-emerald-600 transition';
        tp.className = !isR ? 'bg-emerald-500 text-white px-4 py-1.5 rounded-full transition' : 'px-4 py-1.5 rounded-full text-stone-500 hover:text-emerald-600 transition';
      }
      </script>

      <!-- Feature slider -->
      @php $featSlides = ($featSlides ?? collect())->values(); $featSubs = ($featSubs ?? collect())->values(); @endphp
      @if($featSlides->count())
      <div class="flex items-center justify-between mt-8">
        <div class="flex items-center gap-2"><h3 class="text-[17px] font-extrabold text-stone-900">Feature</h3><span id="feat-counter" class="text-[11px] font-bold text-stone-400">1 / {{ $featSlides->count() }}</span></div>
        <div class="flex gap-1.5">
          <button onclick="featGo(-1)" aria-label="Sebelumnya" class="w-7 h-7 rounded-full bg-white border flex items-center justify-center text-stone-500 hover:border-emerald-500 hover:text-emerald-600 transition">‹</button>
          <button onclick="featGo(1)" aria-label="Berikutnya" class="w-7 h-7 rounded-full bg-emerald-500 text-white flex items-center justify-center hover:bg-emerald-600 transition">›</button>
        </div>
      </div>
      <div id="feat-box" class="reveal relative rounded-2xl overflow-hidden group min-h-[360px] flex items-end mt-3 shadow-sm">
        <img id="feat-img" src="{{ $gambarBerita($featSlides->first(), 1) }}" loading="lazy" decoding="async" class="absolute inset-0 w-full h-full object-cover transition duration-700" alt="{{ $featSlides->first()?->judul ?? 'Feature' }}">
        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent pointer-events-none"></div>
        <div class="relative p-5 md:p-6 text-white w-full">
          <span class="text-[10px] font-extrabold bg-emerald-500 px-2.5 py-1 rounded-md">FEATURE</span>
          <a id="feat-link" href="{{ ($featSlides->first()) ? route('berita.show',$featSlides->first()->slug) : route('berita.index') }}"><h3 id="feat-title" class="font-extrabold text-[20px] md:text-[22px] leading-snug mt-2 max-w-2xl hover:underline">{{ $featSlides->first()->judul ?? 'Kisah Reporter Kabar Pondok dari Tengah Lapangan' }}</h3></a>
          <p id="feat-meta" class="text-[11px] text-white/70 font-semibold mt-1">REDAKSI KAUMAN • 5 HARI AGO</p>
          <div class="grid sm:grid-cols-2 gap-3 mt-4">
            @if($featSubs->count())
            @foreach($featSubs as $sm)
            <a href="{{ route('berita.show', $sm->slug) }}" class="flex gap-2.5 items-center"><img src="{{ $gambarBerita($sm, 2) }}" loading="lazy" decoding="async" alt="{{ $sm->judul }}" class="w-20 h-16 rounded-xl object-cover border-2 border-white/80 shrink-0"><div><p class="text-[13px] font-bold leading-snug">{{ $sm->judul }}</p><p class="text-[10px] text-white/70 font-bold mt-1">{{ $sm->published_at ? strtoupper($sm->published_at->diffForHumans()) : '' }}</p></div></a>
            @endforeach
            @else
            @foreach([[$heroImgs[2],'Khidmah Saat Libur Maulid: Bahagia Melihat Orang Senang','4 MINGGU AGO'],[$heroImgs[3],'Memeras Peluh di Balik Gemuruh Rebana Evaluasi Jamiyah Dufuf','1 BULAN AGO']] as $sm)
            <div class="flex gap-2.5 items-center"><img src="{{ $sm[0] }}" loading="lazy" decoding="async" alt="{{ $sm[1] }}" class="w-20 h-16 rounded-xl object-cover border-2 border-white/80 shrink-0"><div><p class="text-[13px] font-bold leading-snug">{{ $sm[1] }}</p><p class="text-[10px] text-white/70 font-bold mt-1">{{ $sm[2] }}</p></div></div>
            @endforeach
            @endif
          </div>
          <div id="feat-dots" class="flex gap-1.5 mt-4"></div>
        </div>
      </div>
      <script>
      const featData = [
        @foreach($featSlides as $i => $f)
        {img:@json($gambarBerita($f, $i)), title:@json($f->judul), meta:'REDAKSI KAUMAN • {{ $f->published_at ? $f->published_at->diffForHumans() : '' }}', url:'{{ route('berita.show',$f->slug) }}'},
        @endforeach
      ];
      let featIdx = 0, featPaused = false, featTimer = null;
      const FEAT_DUR = 6000;
      // Jadwal berbasis jam dinding (seperti Sorotan): tahan banting saat tab disembunyikan.
      let featT0 = Date.now(), featLast = Date.now();
      function featStop(){ if(window.__featTimer){ clearInterval(window.__featTimer); clearTimeout(window.__featTimer); window.__featTimer = null; } }
      function featSync(){ featT0 = Date.now() - featIdx*FEAT_DUR; featLast = Date.now(); }
      function featRender(){
        if(!featData.length) return;
        const d = featData[featIdx];
        const img = document.getElementById('feat-img');
        if(img){
          if(document.hidden){ img.src = d.img; img.style.opacity = 1; }
          else {
            img.style.opacity = 0;
            let settled = false;
            const settle = ()=>{ if(settled) return; settled = true; img.style.opacity = 1; };
            img.onload = settle;
            img.onerror = settle;
            img.src = d.img;
            setTimeout(settle, 400); // pengaman: cache / src sama tak selalu memicu onload
          }
        }
        document.getElementById('feat-title').textContent = d.title;
        document.getElementById('feat-meta').textContent = d.meta;
        document.getElementById('feat-link').href = d.url;
        document.getElementById('feat-counter').textContent = (featIdx+1)+' / '+featData.length;
        const dots = document.getElementById('feat-dots');
        dots.innerHTML = '';
        featData.forEach((_,i)=>{
          const s = document.createElement('button');
          s.className = 'h-1.5 rounded-full transition-all ' + (i===featIdx ? 'w-8 bg-emerald-400' : 'w-3 bg-white/40 hover:bg-white/70');
          s.setAttribute('aria-label', 'Ke feature ' + (i+1));
          s.onclick = ()=>{ featManual(i); };
          dots.appendChild(s);
        });
      }
      function featManual(i){ featIdx = ((i % featData.length) + featData.length) % featData.length; featRender(); featSync(); }
      function featGo(dir){ featManual(featIdx + dir); }
      function featTick(){
        if(!featData.length) return;
        const now = Date.now();
        if(featPaused || document.hidden){ featT0 += now - featLast; featLast = now; return; }
        featLast = now;
        const should = Math.floor((now - featT0)/FEAT_DUR) % featData.length;
        if(should !== featIdx){ featIdx = should; featRender(); }
      }
      featStop();
      featRender(); featSync();
      window.__featTimer = setInterval(featTick, 500);
      featTimer = window.__featTimer;
      document.getElementById('feat-box')?.addEventListener('mouseenter', ()=>{ featPaused = true; });
      document.getElementById('feat-box')?.addEventListener('mouseleave', ()=>{ featPaused = false; featSync(); });
      // Listener sekali pasang (VS Turbo navigasi): cegah duplikat tiap kunjungan.
      if(!window.__featVisBound){
        window.__featVisBound = true;
        document.addEventListener('visibilitychange', ()=>{ if(!document.hidden) featTick(); });
        window.addEventListener('focus', ()=>featTick());
        window.addEventListener('pageshow', ()=>featTick());
        document.addEventListener('turbo:before-render', ()=>{ featStop(); }, {once:false});
      }
      </script>
      @endif

      <!-- Terbaru -->
      @if(($terbaruList ?? collect())->count())
      <div class="flex items-center justify-between mt-8">
        <h3 class="text-[17px] font-extrabold text-stone-900">Terbaru</h3>
        <a href="{{ route('berita.index') }}" class="text-[11px] text-stone-400 hover:text-emerald-600">View All</a>
      </div>
      <div class="bg-white rounded-2xl border border-stone-100 p-4 md:p-5 mt-3 shadow-sm flex flex-col gap-6">
        @foreach($terbaruList as $i => $tb)
        <a href="{{ route('berita.show',$tb->slug) }}" class="reveal flex flex-col sm:flex-row gap-4 group">
          <img src="{{ $gambarBerita($tb, $i) }}" loading="lazy" decoding="async" class="sm:w-[240px] h-48 sm:h-[160px] rounded-xl object-cover shrink-0" alt="{{ $tb->judul }}">
          <div class="min-w-0">
            <p class="text-[11px] font-bold text-emerald-500 tracking-wider">{{ strtoupper($tb->kategori ?? 'BERITA') }}</p>
            <h4 class="font-extrabold text-[17px] leading-snug mt-1 group-hover:text-emerald-700">{{ $tb->judul }}</h4>
            <p class="text-[13px] text-stone-500 mt-1.5 line-clamp-2">{{ $tb->excerpt }}</p>
            <div class="flex items-center gap-2 mt-3"><div class="w-7 h-7 rounded-full bg-stone-200"></div><p class="text-[11px] font-bold">REDAKSI KAUMAN<br><span class="text-stone-400 font-semibold">{{ $tb->published_at ? $tb->published_at->diffForHumans() : '' }}</span></p><span class="ml-auto text-[11px] font-bold text-emerald-500">KEEP READING</span></div>
          </div>
        </a>
        @endforeach
        <div><a href="{{ route('berita.index') }}" class="inline-block bg-emerald-500 hover:bg-emerald-600 text-white text-[13px] font-bold px-6 py-2.5 rounded-xl transition">Load More →</a></div>
      </div>
      @endif

      <!-- Bahtsul Masail -->
      <div class="flex items-center justify-between mt-8">
        <h3 class="text-[17px] font-extrabold text-stone-900">Bahtsul Masail</h3>
        <div class="flex gap-1.5"><span class="w-7 h-7 rounded-full bg-white border flex items-center justify-center text-stone-400">‹</span><span class="w-7 h-7 rounded-full bg-emerald-500 text-white flex items-center justify-center">›</span></div>
      </div>
      <div class="grid sm:grid-cols-3 gap-4 mt-3">
        @if(($bahtsul ?? collect())->count())
        @foreach($bahtsul as $i => $bm)
        <a href="{{ route('berita.show', $bm->slug) }}" class="reveal bg-white rounded-2xl border overflow-hidden group shadow-sm hover:shadow-lg transition"><img src="{{ $gambarBerita($bm, $i) }}" loading="lazy" decoding="async" alt="{{ $bm->judul }}" class="h-36 w-full object-cover group-hover:scale-105 transition duration-500"><div class="p-3.5"><p class="text-[10px] font-bold text-emerald-500">{{ strtoupper($bm->kategori) }}</p><h4 class="font-bold text-[13px] leading-snug mt-1 line-clamp-3">{{ $bm->judul }}</h4></div></a>
        @endforeach
        @else
        @foreach([[$heroImgs[0],'Hukum Menghadiri Walimah Ursy','FIQIH'],[$heroImgs[1],'Hasil Keputusan Bahtsul Masail Wustho ke-70','ARTIKEL'],[$heroImgs[2],'BMW ke-71, Teguhkan Tradisi Keilmuan','AGENDA']] as $bm)
        <a href="{{ route('berita.index') }}" class="reveal bg-white rounded-2xl border overflow-hidden group shadow-sm hover:shadow-lg transition"><img src="{{ $bm[0] }}" loading="lazy" decoding="async" alt="{{ $bm[1] }}" class="h-36 w-full object-cover group-hover:scale-105 transition duration-500"><div class="p-3.5"><p class="text-[10px] font-bold text-emerald-500">{{ $bm[2] }}</p><h4 class="font-bold text-[13px] leading-snug mt-1 line-clamp-3">{{ $bm[1] }}</h4></div></a>
        @endforeach
        @endif
      </div>
    </div>

    <!-- sidebar kanan -->
    <aside class="lg:col-span-4 flex flex-col gap-6">
      <div class="reveal bg-white rounded-2xl border border-stone-100 p-5 shadow-sm">
        <div class="flex items-center justify-between"><h3 class="text-[17px] font-extrabold">Dawuh Masyayikh</h3><a href="#" class="text-[11px] text-stone-400">View All</a></div>
        <div class="mt-4 flex flex-col gap-5">
          @if(($dawuhs ?? collect())->count())
          @foreach($dawuhs as $i => $dw)
          <a href="{{ route('berita.show', $dw->slug) }}" class="flex gap-3 group"><img src="{{ $gambarBerita($dw, $i) }}" loading="lazy" decoding="async" alt="{{ $dw->judul }}" class="w-16 h-16 rounded-xl object-cover shrink-0"><div><h4 class="font-bold text-[13.5px] leading-snug group-hover:text-emerald-700">{{ $dw->judul }}</h4><p class="text-[11px] text-stone-400 mt-1.5 font-semibold">{{ $dw->published_at ? strtoupper($dw->published_at->diffForHumans()) : '' }}</p></div></a>
          @endforeach
          @else
          <div class="flex gap-3"><img src="{{ $heroImgs[0] }}" loading="lazy" decoding="async" alt="Dawuh masyayikh" class="w-16 h-16 rounded-xl object-cover shrink-0"><div><h4 class="font-bold text-[13.5px] leading-snug">Meneladani Kiai dan Ibu Nyai: Lima Fase Kehidupan</h4><p class="text-[11px] text-stone-400 mt-1.5 font-semibold">4 MINGGU AGO</p></div></div>
          <div class="flex gap-3"><div class="w-16 h-16 rounded-xl bg-stone-100 shrink-0 flex items-center justify-center font-arab text-xl text-stone-400">ن</div><div><h4 class="font-bold text-[13.5px] leading-snug">Nuansa Kesufian dalam Kehidupan KH. Noerhasan</h4><p class="text-[11px] text-stone-400 mt-1.5 font-semibold">1 BULAN AGO</p></div></div>
          <div class="flex gap-3"><div class="w-16 h-16 rounded-xl bg-stone-100 shrink-0 flex items-center justify-center font-arab text-xl text-stone-400">ع</div><div><h4 class="font-bold text-[13.5px] leading-snug">Figur Dahaga Ilmu dengan Segudang Tirakat</h4><p class="text-[11px] text-stone-400 mt-1.5 font-semibold">4 BULAN AGO</p></div></div>
          @endif
        </div>
      </div>
      <div class="reveal bg-white rounded-2xl border border-stone-100 p-5 shadow-sm">
        <h3 class="font-extrabold text-stone-900 text-[22px]">Rekomendasi</h3>
        <div class="w-16 h-1 bg-orange-400 rounded-full mt-3 mb-2"></div>
        <div class="flex flex-col">
          @if(($rekomendasi ?? collect())->count())
          @foreach($rekomendasi as $j => $rec)
          @php $recimg = $gambarBerita($rec, $j); @endphp
          <a href="{{ route('berita.show', $rec->slug) }}" class="flex gap-4 py-5 border-b border-stone-100 last:border-0 group">
            <div class="min-w-0 flex-1">
              <h4 class="font-extrabold text-stone-900 text-[15px] leading-snug line-clamp-2 group-hover:text-orange-600 transition">{{ $rec->judul }}</h4>
              <p class="text-stone-500 text-[13.5px] leading-relaxed mt-1 line-clamp-2">{{ $rec->excerpt }}</p>
              <p class="flex items-center justify-between gap-2 mt-2.5">
                <span class="flex items-center gap-1.5 text-[12.5px] text-stone-500">
                  <svg class="w-3.5 h-3.5 text-amber-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                  {{ $rec->published_at ? $rec->published_at->translatedFormat('d F Y') : '' }}
                </span>
                <span class="bg-[#fdf3e3] text-[#b45309] text-[12px] font-bold px-3 py-1 rounded-lg">{{ ucfirst($rec->kategori ?? 'Berita') }}</span>
              </p>
            </div>
            <img src="{{ $recimg }}" alt="{{ $rec->judul }}" loading="lazy" class="w-[88px] h-[88px] rounded-xl object-cover shrink-0 bg-stone-100 border border-stone-100 group-hover:scale-105 transition duration-500">
          </a>
          @endforeach
          @endif
        </div>
      </div>
      @if(($populerList ?? collect())->count())
      <div class="reveal bg-white rounded-2xl border p-5 shadow-sm">
        <h3 class="text-[15px] font-extrabold">Populer</h3>
        <div class="mt-2 flex flex-col divide-y divide-stone-100">
          @foreach($populerList as $idx=>$pp)
          <a href="{{ route('berita.show',$pp->slug) }}" class="py-3 flex gap-3 group"><span class="text-[13px] font-bold text-stone-300">0{{ $idx+1 }}</span><div><p class="text-[13px] font-bold leading-snug group-hover:text-emerald-700 line-clamp-2">{{ $pp->judul }}</p><p class="text-[10px] text-stone-400 mt-1 font-semibold">REDAKSI KAUMAN • {{ $pp->published_at ? $pp->published_at->diffForHumans() : '' }}</p></div></a>
          @endforeach
        </div>
      </div>
      @endif
      <div class="reveal bg-white rounded-2xl border p-5 shadow-sm">
        <h3 class="text-[17px] font-extrabold text-stone-900">Join Us</h3>
        <div class="grid grid-cols-2 gap-x-3 gap-y-5 mt-4">
          <a href="{{ \App\Models\Setting::get('sosmed_twitter_url', '#') }}" target="_blank" rel="noopener" class="flex gap-2.5 items-center group">
            <span class="w-10 h-10 rounded-[10px] bg-[#1DA1F2] flex items-center justify-center shrink-0"><svg viewBox="0 0 24 24" class="w-5 h-5 fill-white"><path d="M18.9 2H22l-6.8 7.8L23.2 22h-6.3l-4.9-6.4L6.4 22H3.2l7.3-8.3L1.6 2H8l4.4 5.9L18.9 2zm-1.1 18h1.7L7 3.9H5.2L17.8 20z"/></svg></span>
            <span><span class="block text-[13px] font-bold text-stone-800">Twitter</span><span class="block text-[13px] font-extrabold text-sky-500">{{ \App\Models\Setting::get('sosmed_twitter_count', '46.8K') }}</span></span>
          </a>
          <a href="{{ \App\Models\Setting::get('sosmed_instagram_url', '#') }}" target="_blank" rel="noopener" class="flex gap-2.5 items-center group">
            <span class="w-10 h-10 rounded-[10px] flex items-center justify-center shrink-0" style="background:linear-gradient(45deg,#f9ce34,#ee2a7b,#6228d7)"><svg viewBox="0 0 24 24" class="w-5 h-5" fill="none" stroke="white" stroke-width="2"><rect x="2.5" y="2.5" width="19" height="19" rx="5.5"/><circle cx="12" cy="12" r="4.5"/><circle cx="17.6" cy="6.4" r="1.4" fill="white" stroke="none"/></svg></span>
            <span><span class="block text-[13px] font-bold text-stone-800">Instagram</span><span class="block text-[13px] font-extrabold text-rose-500">{{ \App\Models\Setting::get('sosmed_instagram_count', '62.9K') }}</span></span>
          </a>
          <a href="{{ \App\Models\Setting::get('sosmed_youtube_url', '#') }}" target="_blank" rel="noopener" class="flex gap-2.5 items-center group">
            <span class="w-10 h-10 rounded-[10px] bg-[#FF0000] flex items-center justify-center shrink-0"><svg viewBox="0 0 24 24" class="w-5 h-5 fill-white"><path d="M23 12s0-3.4-.4-5c-.2-.9-.9-1.6-1.8-1.8C19.2 4.8 12 4.8 12 4.8s-7.2 0-8.8.4c-.9.2-1.6.9-1.8 1.8C1 8.6 1 12 1 12s0 3.4.4 5c.2.9.9 1.6 1.8 1.8 1.6.4 8.8.4 8.8.4s7.2 0 8.8-.4c.9-.2 1.6-.9 1.8-1.8.4-1.6.4-5 .4-5zM9.8 15.5v-7l6 3.5-6 3.5z"/></svg></span>
            <span><span class="block text-[13px] font-bold text-stone-800">Youtube</span><span class="block text-[13px] font-extrabold text-purple-700">{{ \App\Models\Setting::get('sosmed_youtube_count', '110.6K') }}</span></span>
          </a>
          <a href="{{ \App\Models\Setting::get('sosmed_tiktok_url', '#') }}" target="_blank" rel="noopener" class="flex gap-2.5 items-center group">
            <span class="w-10 h-10 rounded-[10px] bg-black flex items-center justify-center shrink-0"><svg viewBox="0 0 24 24" class="w-5 h-5 fill-white"><path d="M16.6 3c.4 2.2 1.8 3.7 4.1 3.9v3.1c-1.5 0-2.9-.5-4.1-1.3v6.4c0 3.6-2.6 6.1-6 6.1-3.3 0-5.8-2.5-5.8-5.8 0-3.4 2.7-5.9 6.2-5.7v3.2c-1.6-.4-3.1.7-3.1 2.5 0 1.5 1.2 2.7 2.7 2.7 1.6 0 2.8-1.2 2.8-3V3h3.2z"/></svg></span>
            <span><span class="block text-[13px] font-bold text-stone-800">Tiktok</span><span class="block text-[13px] font-extrabold text-stone-900">{{ \App\Models\Setting::get('sosmed_tiktok_count', '50.2K') }}</span></span>
          </a>
        </div>
      </div>

      <!-- Pengisi ruang kosong kanan -->
      <div class="reveal bg-white rounded-2xl border p-5 shadow-sm">
        <div class="flex items-center justify-between"><h3 class="text-[15px] font-extrabold">Agenda Pondok</h3><span class="text-[11px] font-bold bg-amber-100 text-amber-700 px-2.5 py-1 rounded-full">{{ ($agendas ?? collect())->count() }} dekat</span></div>
        <div class="mt-3 flex flex-col gap-2.5">
          @forelse(($agendas ?? collect()) as $ag)
          <div class="flex items-center gap-3 bg-stone-50 border rounded-xl p-2.5 hover:border-emerald-300 transition">
            <div class="w-11 h-11 rounded-lg bg-emerald-950 text-white flex flex-col items-center justify-center shrink-0"><span class="text-[14px] font-extrabold leading-none">{{ $ag->tanggal->format('d') }}</span><span class="text-[9px] font-bold text-amber-300">{{ $bulanId[$ag->tanggal->month] ?? '' }}</span></div>
            <p class="text-[12.5px] font-bold leading-snug">{{ $ag->judul }}{{ $ag->tempat ? ' • '.$ag->tempat : '' }}</p>
          </div>
          @empty
          @foreach([['22','Sep','Sorogan Fathul Qorib • Ba’da Ashar'],['26','Sep','Tasmi Akbar Tahfidz • Aula Utama'],['03','Okt','Haflah & Wisuda Juz • Lapangan']] as $ag)
          <div class="flex items-center gap-3 bg-stone-50 border rounded-xl p-2.5 hover:border-emerald-300 transition">
            <div class="w-11 h-11 rounded-lg bg-emerald-950 text-white flex flex-col items-center justify-center shrink-0"><span class="text-[14px] font-extrabold leading-none">{{ $ag[0] }}</span><span class="text-[9px] font-bold text-amber-300">{{ $ag[1] }}</span></div>
            <p class="text-[12.5px] font-bold leading-snug">{{ $ag[2] }}</p>
          </div>
          @endforeach
          @endforelse
        </div>
      </div>

      <div class="reveal bg-white rounded-2xl border p-5 shadow-sm">
        <div class="flex items-center justify-between"><h3 class="text-[15px] font-extrabold">Galeri Santri</h3><a href="{{ route('galeri.index') }}" class="text-[11px] text-stone-400 hover:text-emerald-600">Lihat →</a></div>
        <div class="grid grid-cols-3 gap-2 mt-3">
          @if(($galeris ?? collect())->count())
          @foreach($galeris->take(6) as $i => $gl)
          <a href="{{ route('galeri.index') }}"><img src="{{ $gl->gambar ? '/storage/'.$gl->gambar : $heroImgs[$i % 4] }}" class="h-20 w-full object-cover rounded-xl hover:scale-105 transition" alt="{{ $gl->judul }}" loading="lazy"></a>
          @endforeach
          @else
          @foreach([0,1,2,3,4,5] as $g)
          <img src="{{ $heroImgs[$g % 4] }}" loading="lazy" decoding="async" class="h-20 w-full object-cover rounded-xl hover:scale-105 transition" alt="Galeri kegiatan santri">
          @endforeach
          @endif
        </div>
      </div>
    </aside>
  </div>
</div>
</section>

@endsection
