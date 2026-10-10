@extends('layouts.app')
@section('title', 'Santri')
@section('content')
<div class="bg-white">
<div class="max-w-7xl mx-auto px-4 py-8 md:py-10">
  <p class="text-[12px] font-bold text-stone-400 tracking-wider"><a href="{{ route('home') }}" class="hover:text-orange-600 transition">Beranda</a> <span class="mx-1">/</span> <span class="text-stone-600">Santri</span></p>
  <h1 class="font-extrabold text-stone-900 text-[26px] md:text-[30px] leading-tight mt-1.5">Santri</h1>
  <div class="w-16 h-1 bg-orange-400 rounded-full mt-3 mb-6"></div>

  {{-- Statistik count-up (dari Pengaturan Website) --}}
  <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
    @php $S = \App\Models\Setting::class; @endphp
    @foreach([1,2,3,4] as $i)
    <div class="reveal rounded-2xl bg-emerald-950 text-white p-6 text-center relative overflow-hidden">
      <p class="font-extrabold text-[32px] leading-none text-amber-400"><span class="count" data-target="{{ $S::get("santri_stat_{$i}_angka", [500,30,40,24][$i-1]) }}">0</span><span class="text-[18px]">{{ $S::get("santri_stat_{$i}_satuan", ['+','Juz','+','Jam'][$i-1]) }}</span></p>
      <p class="text-[12px] font-bold tracking-widest text-emerald-100 mt-2">{{ strtoupper($S::get("santri_stat_{$i}_label", ['Santri Mukim','Target Tahfidz','Ustadz & Musyrif','Pembinaan'][$i-1])) }}</p>
    </div>
    @endforeach
  </div>

  <div class="grid lg:grid-cols-2 gap-5 mt-6">
    <div class="reveal border border-stone-200 rounded-2xl p-6 md:p-7 bg-white">
      <h2 class="font-extrabold text-stone-900 text-[18px]">🕐 Jadwal Harian</h2>
      <ul class="mt-3 space-y-2.5 text-[14px] text-stone-600">
        @foreach(explode("\n", $S::get('santri_jadwal', "03.30 | Tahajud & murojaah")) as $j)
        @php $parts = explode('|', $j, 2); @endphp
        @if(trim($j) !== '')<li class="flex gap-3"><b class="text-stone-900 w-24 shrink-0">{{ trim($parts[0]) }}</b> {{ trim($parts[1] ?? '') }}</li>@endif
        @endforeach
      </ul>
    </div>
    <div class="reveal border border-stone-200 rounded-2xl p-6 md:p-7 bg-white">
      <h2 class="font-extrabold text-stone-900 text-[18px]">📜 Tata Tertib Singkat</h2>
      <ul class="mt-3 space-y-2.5 text-[14px] text-stone-600">
        @foreach(explode("\n", $S::get('santri_tatib', "Menjaga shalat berjamaah lima waktu")) as $t)
        @if(trim($t) !== '')<li class="flex gap-3"><span class="text-amber-500 font-extrabold">✓</span> {{ trim($t) }}</li>@endif
        @endforeach
      </ul>
    </div>
  </div>

  <div class="reveal max-w-3xl text-stone-600 text-[15px] leading-relaxed mt-6">
    <p>{{ $S::get('santri_intro', 'Kehidupan santri berjalan 24 jam dalam pengawasan musyrif asrama.') }}</p>
    <a href="{{ route('ppdb.form') }}" class="inline-block mt-4 bg-emerald-950 hover:bg-emerald-800 text-white text-[12.5px] font-extrabold tracking-widest px-6 py-3 rounded-full transition">DAFTAR JADI SANTRI →</a>
  </div>
</div>
</div>
<script>
(function(){
  const els = document.querySelectorAll('.count');
  if(!('IntersectionObserver' in window)){ els.forEach(e=>e.textContent=e.dataset.target); return; }
  const io = new IntersectionObserver((es)=>{
    es.forEach(en=>{
      if(!en.isIntersecting) return;
      const el = en.target, target = +el.dataset.target, t0 = performance.now();
      (function tick(t){
        const p = Math.min((t-t0)/1200, 1);
        el.textContent = Math.round(target * (1-Math.pow(1-p,3)));
        if(p<1) requestAnimationFrame(tick);
      })(t0);
      io.unobserve(el);
    });
  },{threshold:.4});
  els.forEach(e=>io.observe(e));
})();
</script>
@endsection
