@extends('layouts.app')
@section('title', 'Pencarian')
@section('content')
<div class="bg-white">
<div class="max-w-5xl mx-auto px-4 py-8 md:py-10">
  <p class="text-[12px] font-bold text-stone-400 tracking-wider"><a href="{{ route('home') }}" class="hover:text-orange-600 transition">Beranda</a> <span class="mx-1">/</span> <span class="text-stone-600">Pencarian</span></p>
  <h1 class="font-extrabold text-stone-900 text-[26px] md:text-[30px] leading-tight mt-1.5">Pencarian</h1>
  <div class="w-16 h-1 bg-orange-400 rounded-full mt-3 mb-6"></div>

  <form action="{{ route('search') }}" method="GET" role="search" class="flex items-center gap-2 border border-stone-300 bg-stone-50 px-4 py-3 focus-within:border-emerald-500 focus-within:bg-white transition">
    <i class="fa-solid fa-magnifying-glass text-stone-400 text-[14px]"></i>
    <input type="search" name="q" value="{{ $q }}" maxlength="100" autocomplete="off" enterkeyhint="search"
           placeholder="Cari berita, program, organisasi, atau layanan..."
           class="min-w-0 flex-1 bg-transparent border-0 outline-none text-[14px] text-stone-800 placeholder-stone-400">
    <button type="submit" class="shrink-0 bg-emerald-950 text-white text-[12px] font-extrabold tracking-wide px-4 py-2 hover:bg-emerald-800 transition">Cari</button>
  </form>

  @if($q === '')
    <div class="mt-10 border border-stone-200 rounded-2xl p-8 text-center">
      <div class="w-12 h-12 mx-auto rounded-full bg-stone-100 flex items-center justify-center text-stone-400">
        <i class="fa-solid fa-magnifying-glass text-[18px]"></i>
      </div>
      <p class="font-extrabold text-stone-800 mt-4">Ketik kata kunci untuk mulai mencari</p>
      <p class="text-[13.5px] text-stone-500 mt-1">Cari berita, artikel, program, organisasi, lembaga, masyayikh, atau halaman layanan.</p>
    </div>
  @else
    <div class="mt-6 flex flex-wrap items-baseline gap-x-2 gap-y-1">
      <p class="text-[14px] text-stone-500">Hasil untuk</p>
      <p class="font-extrabold text-stone-900">“{{ $q }}”</p>
      <p class="text-[13px] text-stone-400">• {{ $total }} hasil ditemukan</p>
    </div>

    @if($total === 0)
      <div class="mt-8 border border-stone-200 rounded-2xl p-8 text-center">
        <p class="font-extrabold text-stone-800">Tidak ada hasil yang cocok</p>
        <p class="text-[13.5px] text-stone-500 mt-1">Coba kata kunci lain yang lebih singkat atau gunakan istilah yang berbeda.</p>
        <a href="{{ route('home') }}" class="inline-block mt-4 text-[13px] font-extrabold text-emerald-700 hover:text-emerald-900 transition">Kembali ke Beranda →</a>
      </div>
    @else
      @foreach($groups as $label => $items)
        <section class="mt-8">
          <div class="flex items-center justify-between gap-3 border-b border-stone-200 pb-3">
            <h2 class="font-extrabold text-stone-900 text-[17px]">{{ $label }}</h2>
            <span class="text-[11px] font-bold text-stone-400 bg-stone-100 px-2.5 py-1 rounded-full">{{ $items->count() }}</span>
          </div>
          <div class="divide-y divide-stone-100">
            @foreach($items as $item)
              <a href="{{ $item['url'] }}" class="block py-4 group">
                <div class="flex flex-wrap items-center gap-2">
                  <h3 class="font-extrabold text-stone-900 text-[15px] group-hover:text-emerald-700 transition">{{ $item['title'] }}</h3>
                  @if($item['meta'] !== '')
                    <span class="text-[10.5px] font-bold text-stone-400 tracking-wide">{{ $item['meta'] }}</span>
                  @endif
                </div>
                @if($item['description'] !== '')
                  <p class="text-[13.5px] text-stone-500 leading-relaxed mt-1 line-clamp-2">{{ $item['description'] }}</p>
                @endif
              </a>
            @endforeach
          </div>
        </section>
      @endforeach
    @endif
  @endif
</div>
</div>
@endsection
