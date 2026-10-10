@extends('layouts.app')
@section('title', $judulHal ?? 'Berita')
@section('content')
<div class="bg-white">
<div class="max-w-7xl mx-auto px-4 py-8 md:py-10">
  <p class="text-[12px] font-bold text-stone-400 tracking-wider"><a href="{{ route('home') }}" class="hover:text-orange-600 transition">Beranda</a> <span class="mx-1">/</span> <span class="text-stone-600">{{ $judulHal ?? 'Berita' }}</span></p>
  <h1 class="font-extrabold text-stone-900 text-[26px] md:text-[30px] leading-tight mt-1.5">{{ $judulHal ?? 'Berita' }}</h1>
  <div class="w-16 h-1 bg-orange-400 rounded-full mt-3 mb-6"></div>
  <div class="grid lg:grid-cols-3 gap-8 items-start">

    {{-- Daftar berita --}}
    <div class="lg:col-span-2 min-w-0">
      @forelse($beritas as $i => $b)
      @php $img = $b->gambar ? asset('storage/'.$b->gambar) : asset('/images/hero/slide-'.(($i%4)+1).'.jpg'); @endphp
      <a href="{{ route('berita.show', $b->slug) }}" class="flex gap-5 md:gap-6 pb-7 mb-7 border-b border-stone-100 last:border-0 last:mb-0 last:pb-0 group">
        <div class="relative shrink-0 overflow-hidden rounded-2xl">
          <img src="{{ $img }}" alt="{{ $b->judul }}" loading="lazy" class="w-32 h-40 md:w-52 md:h-56 object-cover bg-stone-100 group-hover:scale-105 transition duration-500">
        </div>
        <div class="min-w-0">
          <p class="flex items-center gap-3 text-[14px] text-stone-500">{{ $b->published_at ? $b->published_at->translatedFormat('d F Y') : '' }} <span class="bg-amber-500 text-white text-[12px] font-bold px-3 py-1 rounded-lg">{{ ucfirst($b->kategori ?? 'Berita') }}</span></p>
          <h2 class="font-extrabold text-stone-900 text-[17px] md:text-[21px] leading-snug mt-3 group-hover:text-orange-600 transition">{{ $b->judul }}</h2>
          <p class="text-stone-500 text-[14px] md:text-[15px] leading-relaxed mt-2 line-clamp-3">{{ $b->excerpt }}</p>
        </div>
      </a>
      @empty
      <p class="text-center text-stone-500 py-16">Belum ada berita. Silakan kembali lagi.</p>
      @endforelse

      {{-- Pagination ala Sukorejo --}}
      @if($beritas->hasPages())
      @php
        $cur = $beritas->currentPage();
        $last = $beritas->lastPage();
        $from = max(1, min($cur - 3, $last - 6));
        $to = min($last, $from + 6);
        $from = max(1, $to - 6);
      @endphp
      <div class="flex items-center justify-between gap-3 border-t border-stone-200 mt-2 pt-6 text-[14px] font-bold">
        @if($beritas->onFirstPage())
        <span class="flex items-center gap-2 px-4 py-2.5 rounded-full bg-stone-100 text-stone-400 cursor-not-allowed select-none"><span>←</span><span class="hidden sm:inline">Sebelumnya</span></span>
        @else
        <a href="{{ $beritas->previousPageUrl() }}" class="flex items-center gap-2 px-4 py-2.5 rounded-full bg-stone-900 text-white hover:bg-orange-500 transition shadow-sm hover:shadow-md"><span>←</span><span class="hidden sm:inline">Sebelumnya</span></a>
        @endif
        <div class="flex items-center gap-1.5 md:gap-2 overflow-x-auto">
          @for($p = $from; $p <= $to; $p++)
            @if($p == $cur)
            <span class="w-10 h-10 rounded-full bg-gradient-to-br from-amber-400 to-orange-500 text-white flex items-center justify-center shadow-md shadow-orange-200 shrink-0">{{ $p }}</span>
            @else
            <a href="{{ $beritas->url($p) }}" class="w-10 h-10 rounded-full bg-white border border-stone-200 text-stone-500 hover:border-orange-400 hover:text-orange-600 flex items-center justify-center transition shrink-0">{{ $p }}</a>
            @endif
          @endfor
        </div>
        @if($beritas->hasMorePages())
        <a href="{{ $beritas->nextPageUrl() }}" class="flex items-center gap-2 px-4 py-2.5 rounded-full bg-stone-900 text-white hover:bg-orange-500 transition shadow-sm hover:shadow-md"><span class="hidden sm:inline">Selanjutnya</span><span>→</span></a>
        @else
        <span class="flex items-center gap-2 px-4 py-2.5 rounded-full bg-stone-100 text-stone-400 cursor-not-allowed select-none"><span class="hidden sm:inline">Selanjutnya</span><span>→</span></span>
        @endif
      </div>
      @endif
    </div>

    {{-- Rekomendasi --}}
    <aside class="border border-stone-200 rounded-2xl p-6 lg:sticky lg:top-24 bg-white">
      <h3 class="font-extrabold text-stone-900 text-[22px]">Rekomendasi</h3>
      <div class="w-16 h-1 bg-orange-400 rounded-full mt-3 mb-2"></div>
      <div class="flex flex-col">
        @forelse(($rekomendasi ?? collect()) as $j => $r)
        @php $rimg = $r->gambar ? asset('storage/'.$r->gambar) : asset('/images/hero/slide-'.(($j%4)+1).'.jpg'); @endphp
        <a href="{{ route('berita.show', $r->slug) }}" class="flex gap-4 py-5 border-b border-stone-100 last:border-0 group">
          <div class="min-w-0 flex-1">
            <h4 class="font-extrabold text-stone-900 text-[15px] leading-snug line-clamp-2 group-hover:text-orange-600 transition">{{ $r->judul }}</h4>
            <p class="text-stone-500 text-[13.5px] leading-relaxed mt-1 line-clamp-2">{{ $r->excerpt }}</p>
            <p class="flex items-center justify-between gap-2 mt-2.5">
              <span class="flex items-center gap-1.5 text-[12.5px] text-stone-500">
                <svg class="w-3.5 h-3.5 text-amber-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                {{ $r->published_at ? $r->published_at->translatedFormat('d F Y') : '' }}
              </span>
              <span class="bg-[#fdf3e3] text-[#b45309] text-[12px] font-bold px-3 py-1 rounded-lg">{{ ucfirst($r->kategori ?? 'Berita') }}</span>
            </p>
          </div>
          <img src="{{ $rimg }}" alt="{{ $r->judul }}" loading="lazy" class="w-[88px] h-[88px] rounded-xl object-cover shrink-0 bg-stone-100 border border-stone-100 group-hover:scale-105 transition duration-500">
        </a>
        @empty
        <p class="text-sm text-stone-500 py-6">Belum ada rekomendasi.</p>
        @endforelse
      </div>
    </aside>

  </div>
</div>
</div>
<style>
.line-clamp-2{display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
.line-clamp-3{display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden}
</style>
@endsection
