@extends('layouts.app')
@section('title', 'Galeri')
@section('content')
<div class="bg-white">
<div class="max-w-7xl mx-auto px-4 py-8 md:py-10">
  <p class="text-[12px] font-bold text-stone-400 tracking-wider"><a href="{{ route('home') }}" class="hover:text-orange-600 transition">Beranda</a> <span class="mx-1">/</span> <span class="text-stone-600">Galeri</span></p>
  <h1 class="font-extrabold text-stone-900 text-[26px] md:text-[30px] leading-tight mt-1.5">Galeri Kegiatan</h1>
  <div class="w-16 h-1 bg-orange-400 rounded-full mt-3 mb-6"></div>

  <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
  @forelse($galeris as $g)
  @php $gimg = $g->gambar ? asset('storage/'.$g->gambar) : null; @endphp
  <button data-full="{{ $gimg }}" data-caption="{{ $g->judul }}" class="reveal gal-item text-left bg-white border border-stone-200 rounded-2xl overflow-hidden hover:shadow-xl hover:-translate-y-1 transition group">
    @if($gimg)
    <div class="h-40 overflow-hidden"><img src="{{ $gimg }}" alt="{{ $g->judul }}" loading="lazy" class="w-full h-full object-cover group-hover:scale-110 transition duration-500"></div>
    @else
    <div class="h-40 bg-amber-50 flex items-center justify-center text-4xl group-hover:scale-105 transition duration-500">📷</div>
    @endif
    <div class="p-3"><p class="font-bold text-[13.5px] text-stone-800 line-clamp-2">{{ $g->judul }}</p><p class="text-[11.5px] text-stone-400 mt-0.5">{{ ucfirst($g->kategori ?? 'kegiatan') }}</p></div>
  </button>
  @empty
  @for($i=1;$i<=8;$i++)
  <div class="reveal bg-white border border-stone-200 rounded-2xl overflow-hidden hover:shadow-xl hover:-translate-y-1 transition group"><div class="h-40 bg-amber-50 flex items-center justify-center text-4xl">📷</div><div class="p-3"><p class="font-bold text-[13.5px]">Kegiatan Santri {{ $i }}</p><p class="text-[11.5px] text-stone-400">kegiatan</p></div></div>
  @endfor
  @endforelse
  </div>
  @if(method_exists($galeris,'hasPages') && $galeris->hasPages())
  <div class="mt-8">{{ $galeris->links() }}</div>
  @endif
</div>
</div>

{{-- Lightbox --}}
<div id="lightbox" class="fixed inset-0 z-[60] hidden items-center justify-center bg-black/85 p-4">
  <button id="lb-close" aria-label="Tutup" class="absolute top-4 right-5 text-white text-3xl hover:text-amber-400 transition">×</button>
  <figure class="max-w-3xl w-full">
    <img id="lb-img" src="" alt="Pratinjau foto galeri" class="w-full max-h-[75vh] object-contain rounded-xl">
    <figcaption id="lb-cap" class="text-white/90 text-center text-sm font-bold mt-3"></figcaption>
  </figure>
</div>
<script>
(function(){
  const lb = document.getElementById('lightbox');
  const img = document.getElementById('lb-img');
  const cap = document.getElementById('lb-cap');
  function open(src, caption){
    if(!src) return;
    img.src = src; img.alt = caption || ''; cap.textContent = caption || '';
    lb.classList.remove('hidden'); lb.classList.add('flex');
    document.body.style.overflow = 'hidden';
  }
  function close(){ lb.classList.add('hidden'); lb.classList.remove('flex'); img.src=''; document.body.style.overflow=''; }
  document.querySelectorAll('.gal-item').forEach(b=>b.addEventListener('click',()=>open(b.dataset.full, b.dataset.caption)));
  document.getElementById('lb-close').addEventListener('click', close);
  lb.addEventListener('click', (e)=>{ if(e.target === lb) close(); });
  document.addEventListener('keydown', (e)=>{ if(e.key === 'Escape') close(); });
})();
</script>
<style>.line-clamp-2{display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}</style>
@endsection
