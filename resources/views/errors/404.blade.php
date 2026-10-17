<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="noindex">
<title>Lost in Space — 404 · PP Kunuuzul Imam Kauman</title>
<link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{
  font-family:'Poppins',system-ui,sans-serif;
  background-color:#080811;
  background-image:radial-gradient(circle at 50% 20%,#1a1a3a 0%,#080811 70%);
  color:#fff;min-height:100vh;display:flex;align-items:center;justify-content:center;
  overflow:hidden;position:relative;padding:24px;
}
.planet{position:absolute;border-radius:50%;opacity:.15;z-index:0;will-change:transform}
.planet-1{width:300px;height:300px;background:linear-gradient(135deg,#38d9a9,#1a1a3a);bottom:-80px;left:-80px;box-shadow:inset -20px -20px 50px rgba(0,0,0,.8)}
.planet-2{width:150px;height:150px;background:linear-gradient(135deg,#7950f2,#38d9a9);top:40px;right:-40px}
.planet-3{width:90px;height:90px;background:linear-gradient(135deg,#38d9a9,#0b3d31);top:70%;right:8%;opacity:.12}
.star{position:absolute;width:2px;height:2px;background:#fff;border-radius:50%;opacity:.6;z-index:0}
.container{text-align:center;max-width:560px;width:100%;z-index:1;perspective:900px}
.hero{display:flex;align-items:center;justify-content:center;position:relative;margin-bottom:22px}
.number-4{font-size:8.5rem;font-weight:800;color:#fff;text-shadow:0 10px 30px rgba(0,0,0,.5);z-index:1;user-select:none;line-height:1}
.circle{
  width:190px;height:190px;border-radius:50%;
  border:4px solid #38d9a9;display:flex;align-items:center;justify-content:center;
  margin:0 12px;position:relative;background:rgba(25,25,50,.4);
  box-shadow:0 0 30px rgba(56,217,169,.25),inset 0 0 20px rgba(56,217,169,.1);
}
.circle::before{content:"";position:absolute;inset:-14px;border-radius:50%;border:1px dashed rgba(56,217,169,.35);animation:spin 26s linear infinite}
@keyframes spin{to{transform:rotate(360deg)}}
.inner-hero{position:relative;will-change:transform;transition:transform .12s ease-out}
.astronaut{width:130px;height:auto;filter:drop-shadow(0 8px 18px rgba(0,0,0,.4));animation:bob 4.5s ease-in-out infinite}
@keyframes bob{0%,100%{transform:translateY(0)}50%{transform:translateY(-7px)}}
.content{margin-top:8px}
.error-code{color:#38d9a9;font-size:.9rem;letter-spacing:4px;font-weight:600;margin-bottom:8px;text-transform:uppercase}
h1{font-size:2.5rem;font-weight:700;margin-bottom:12px;background:linear-gradient(180deg,#fff,#94a3b8);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text}
.description{color:#94a3b8;font-size:.95rem;line-height:1.65;margin-bottom:28px;padding:0 16px}
.btns{display:flex;gap:12px;justify-content:center;flex-wrap:wrap}
.btn-home{display:inline-flex;align-items:center;gap:10px;padding:12px 28px;border-radius:30px;border:1px solid rgba(255,255,255,.2);background:rgba(255,255,255,.05);color:#fff;text-decoration:none;font-size:.95rem;font-weight:600;transition:all .3s cubic-bezier(.4,0,.2,1);box-shadow:0 4px 15px rgba(0,0,0,.2)}
.btn-home:hover{background:#38d9a9;color:#080811;border-color:#38d9a9;transform:translateY(-3px);box-shadow:0 6px 20px rgba(56,217,169,.4)}
.btn-home .arrow{transition:transform .3s ease}
.btn-home:hover .arrow{transform:translateX(-4px)}
.btn-ghost{display:inline-flex;align-items:center;gap:8px;padding:12px 24px;border-radius:30px;border:1px solid transparent;color:#94a3b8;text-decoration:none;font-size:.9rem;font-weight:600;transition:color .25s}
.btn-ghost:hover{color:#38d9a9}
@media(max-width:560px){
  .number-4{font-size:5.5rem}
  .circle{width:140px;height:140px;margin:0 8px}
  .astronaut{width:96px}
  h1{font-size:1.9rem}
}
@media(prefers-reduced-motion:reduce){.astronaut,.circle::before{animation:none}}
</style>
</head>
<body>
<div class="planet planet-1" data-depth="6"></div>
<div class="planet planet-2" data-depth="10"></div>
<div class="planet planet-3" data-depth="14"></div>
@for($i=0;$i<40;$i++)
<span class="star" style="top:{{ mt_rand(0,100) }}%;left:{{ mt_rand(0,100) }}%"></span>
@endfor

<div class="container">
  <div class="hero">
    <span class="number-4">4</span>
    <div class="circle">
      <div class="inner-hero" id="hero">
        <svg class="astronaut" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
          <circle cx="100" cy="90" r="42" fill="#f4f6fb"/>
          <rect x="72" y="68" width="56" height="38" rx="13" fill="#141432"/>
          <rect x="82" y="76" width="36" height="22" rx="7" fill="#38d9a9" fill-opacity=".8"/>
          <path d="M58 138c0-21 17-38 38-38h8c21 0 38 17 38 38v28H58v-28z" fill="#f4f6fb"/>
          <rect x="88" y="120" width="24" height="18" rx="5" fill="#38d9a9" fill-opacity=".55"/>
          <path d="M138 116c22 0 40-30 18-52-14-14-38-8-42-2" stroke="#38d9a9" stroke-width="4" stroke-linecap="round"/>
          <circle cx="100" cy="90" r="42" stroke="#c9d0df" stroke-width="2"/>
        </svg>
      </div>
    </div>
    <span class="number-4">4</span>
  </div>

  <div class="content">
    <p class="error-code">Error 404</p>
    <h1>Lost in space</h1>
    <p class="description">Halaman yang Anda cari sudah melayang keluar orbit — tidak ada atau sudah dipindahkan.</p>
    <div class="btns">
      <a href="{{ route('home') }}" class="btn-home"><span class="arrow">&larr;</span> Take me home</a>
      <a href="{{ route('ppdb.form') }}" class="btn-ghost">Daftar PSB &rarr;</a>
    </div>
  </div>
</div>

<script>
(function(){
  var hero = document.getElementById('hero');
  var layers = document.querySelectorAll('[data-depth]');
  var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  if(reduce) return;
  window.addEventListener('mousemove', function(e){
    var cx = window.innerWidth/2, cy = window.innerHeight/2;
    var mx = (e.clientX-cx)/20, my = (e.clientY-cy)/20;
    hero.style.transform = 'rotateY('+mx+'deg) rotateX('+(-my)+'deg)';
    layers.forEach(function(l){
      var d = parseFloat(l.dataset.depth)||6;
      l.style.transform = 'translate('+(e.clientX-cx)/d*-1+'px,'+(e.clientY-cy)/d*-1+'px)';
    });
  });
  window.addEventListener('mouseleave', function(){
    hero.style.transform = 'rotateY(0deg) rotateX(0deg)';
    layers.forEach(function(l){ l.style.transform=''; });
  });
})();
</script>
</body>
</html>
