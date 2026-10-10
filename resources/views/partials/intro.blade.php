{{-- Intro splash: zoom-in logo pondok, otomatis keluar website. Tanpa tirai / tombol. --}}
<link rel="preload" as="image" href="/images/tirai.jpg">
<div id="kauman-intro" aria-hidden="true">
  <div class="ki-logo-wrap">
    <div class="ki-logo-ring">
      <img src="{{ asset('images/logo-pondok.png') }}" alt="" class="ki-logo">
    </div>
    <p class="ki-eyebrow">PONDOK PESANTREN</p>
    <h1 class="ki-title">KUNUUZUL IMAM<br>KAUMAN</h1>
  </div>
</div>

<style>
#kauman-intro{position:fixed;inset:0;z-index:9999;display:flex;align-items:center;justify-content:center;overflow:hidden;background-color:#022c22;background-image:linear-gradient(180deg,rgba(0,0,0,.72) 0%,rgba(0,0,0,.58) 50%,rgba(0,0,0,.78) 100%),url('/images/tirai.jpg');background-size:cover,cover;background-position:center,center;background-repeat:no-repeat,no-repeat;transition:opacity .6s ease,visibility .6s,transform .7s ease;}
#kauman-intro.zoom-out{opacity:0;visibility:hidden;transform:scale(1.18);pointer-events:none;}
html{scrollbar-gutter:stable;}
body.ki-lock{overflow:hidden;}
.ki-logo-wrap{text-align:center;padding:0 24px;animation:kiZoomIn 1.6s cubic-bezier(.2,.7,.2,1) both;}
@keyframes kiZoomIn{0%{opacity:0;transform:scale(.6);}60%{opacity:1;transform:scale(1.04);}100%{opacity:1;transform:scale(1);}}
.ki-logo-ring{width:120px;height:120px;margin:0 auto;border-radius:9999px;background:#fff;display:flex;align-items:center;justify-content:center;box-shadow:0 0 0 5px rgba(251,191,36,.55),0 0 60px rgba(251,191,36,.35),0 20px 50px rgba(0,0,0,.5);overflow:hidden;animation:kiRing 1.6s ease both;}
@keyframes kiRing{0%{box-shadow:0 0 0 0 rgba(251,191,36,0);}100%{box-shadow:0 0 0 5px rgba(251,191,36,.55),0 0 60px rgba(251,191,36,.35),0 20px 50px rgba(0,0,0,.5);}}
.ki-logo{width:92px;height:auto;object-fit:contain;}
.ki-eyebrow{margin-top:18px;font-size:11px;font-weight:800;letter-spacing:.4em;color:#a7f3d0;transition:opacity .3s ease;}
.ki-title{margin-top:8px;font-weight:800;font-size:clamp(30px,6vw,48px);line-height:1.08;letter-spacing:.03em;color:#fff;text-shadow:0 4px 24px rgba(0,0,0,.5);transition:opacity .3s ease;}
/* Fase 2: teks menghilang, kamera nge-zoom masuk ke logo */
#kauman-intro.zoom-logo .ki-logo-wrap{animation:none;}
#kauman-intro.zoom-logo .ki-eyebrow,#kauman-intro.zoom-logo .ki-title{opacity:0;}
#kauman-intro.zoom-logo .ki-logo-ring{animation:none;transform:scale(22);transition:transform 1s cubic-bezier(.5,0,.75,.4);box-shadow:0 0 0 0 rgba(251,191,36,0);}
@media (prefers-reduced-motion:reduce){.ki-logo-wrap,.ki-logo-ring{animation:none !important;}#kauman-intro{transition:none;}}
</style>

<script>
(function(){
  var box = document.getElementById('kauman-intro');
  if(!box) return;
  // Turbo: hanya tampil pada load awal, navigasi dalam situs langsung dilewati
  if(window.__kiShownOnce){ box.remove(); return; }
  window.__kiShownOnce = true;
  if(window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches){
    box.remove(); return;
  }
  // Tampil 1x per sesi browser
  try{
    if(sessionStorage.getItem('kauman_intro_seen') === '1'){ box.remove(); return; }
  }catch(e){}
  document.body.classList.add('ki-lock');
  // Fase 1: logo muncul (0–1,2 dtk) → Fase 2: zoom masuk ke logo → langsung web
  setTimeout(function(){ box.classList.add('zoom-logo'); }, 1200);
  setTimeout(function(){
    box.classList.add('zoom-out');
    try{ sessionStorage.setItem('kauman_intro_seen','1'); }catch(e){}
    document.body.classList.remove('ki-lock');
    setTimeout(function(){ box.remove(); }, 700);
  }, 2000);
  document.addEventListener('turbo:before-visit', function(){ box.remove(); document.body.classList.remove('ki-lock'); });
})();
</script>
