@extends('layouts.app')
@section('title', 'Organisasi')
@section('content')
<div class="bg-white">
<div class="max-w-5xl mx-auto px-4 py-8 md:py-10">
  <p class="text-[12px] font-bold text-stone-400 tracking-wider"><a href="{{ route('home') }}" class="hover:text-orange-600 transition">Beranda</a> <span class="mx-1">/</span> <span class="text-stone-600">Organisasi</span></p>
  <h1 class="font-extrabold text-stone-900 text-[26px] md:text-[30px] leading-tight mt-1.5">Organisasi</h1>
  <div class="w-16 h-1 bg-orange-400 rounded-full mt-3 mb-6"></div>
  <p class="text-[14px] text-stone-500 leading-relaxed max-w-2xl">{{ \App\Models\Setting::get('organisasi_intro') }}</p>

  @php
    // Fallback ke Pengaturan bila tabel masih kosong
    $parseOrg = function(string $key) {
      $raw = (string) \App\Models\Setting::get($key, '');
      return collect(preg_split('/\r\n|\r|\n/', $raw))->map(fn($l) => trim($l))->filter()->map(function($l) {
        $p = array_map('trim', explode('|', $l, 3));
        return (object)['singkatan' => $p[0] ?? '', 'nama' => $p[1] ?? '', 'deskripsi_singkat' => $p[2] ?? '', 'slug' => null, 'logo' => null];
      })->filter(fn($o) => $o->singkatan !== '')->values();
    };
    $dalam = (isset($orgDalam) && $orgDalam->count()) ? $orgDalam : $parseOrg('organisasi_dalam');
    $luar = (isset($orgLuar) && $orgLuar->count()) ? $orgLuar : $parseOrg('organisasi_luar');
    $orgLogo = function($o) {
      if (!empty($o->logo)) {
        $g = $o->logo;
        if (str_starts_with($g, '/') || str_starts_with($g, 'http')) return $g;
        return '/storage/'.$g;
      }
      return null;
    };
  @endphp

  @if($dalam->count())
  <h2 class="font-extrabold text-emerald-950 text-[18px] mt-8">Di Dalam Pesantren</h2>
  <div class="w-12 h-1 bg-orange-400 rounded-full mt-2 mb-5"></div>
  <div class="grid sm:grid-cols-2 gap-5">
    @foreach($dalam as $o)
    @php $logo = $orgLogo($o); $link = !empty($o->slug) ? route('organisasi.show', $o->slug) : null; @endphp
    @if($link)
    <a href="{{ $link }}" class="reveal border border-stone-200 rounded-2xl p-6 bg-white hover:shadow-lg hover:-translate-y-1 transition group block">
    @else
    <div class="reveal border border-stone-200 rounded-2xl p-6 bg-white hover:shadow-lg hover:-translate-y-1 transition group block">
    @endif
      @if($logo)
      <img src="{{ $logo }}" alt="{{ $o->singkatan }}" class="h-12 w-auto object-contain">
      @else
      <div class="w-11 h-11 rounded-xl bg-emerald-950 text-amber-400 flex items-center justify-center font-extrabold text-lg shrink-0">{{ mb_substr($o->singkatan, 0, 1) }}</div>
      @endif
      <h3 class="font-extrabold text-stone-900 text-[16px] mt-3 group-hover:text-orange-600 transition">{{ $o->singkatan }}</h3>
      @if(!empty($o->nama))<p class="text-[12px] font-bold text-amber-600 tracking-wide mt-0.5">{{ $o->nama }}</p>@endif
      @if(!empty($o->deskripsi_singkat))<p class="text-stone-500 text-[13.5px] mt-2 leading-relaxed">{{ $o->deskripsi_singkat }}</p>@endif
      @if($link)<span class="text-orange-600 font-extrabold text-[13px] mt-4 inline-block group-hover:translate-x-1 transition-transform">Detail →</span>@endif
    @if($link)
    </a>
    @else
    </div>
    @endif
    @endforeach
  </div>
  @endif

  @if($luar->count())
  <h2 class="font-extrabold text-emerald-950 text-[18px] mt-8">Di Luar Pesantren <span class="text-[13px] font-bold text-stone-400">— di bawah naungan pondok</span></h2>
  <div class="w-12 h-1 bg-orange-400 rounded-full mt-2 mb-5"></div>
  <div class="grid sm:grid-cols-2 gap-5">
    @foreach($luar as $o)
    @php $logo = $orgLogo($o); $link = !empty($o->slug) ? route('organisasi.show', $o->slug) : null; @endphp
    @if($link)
    <a href="{{ $link }}" class="reveal border border-stone-200 rounded-2xl p-6 bg-white hover:shadow-lg hover:-translate-y-1 transition group block">
    @else
    <div class="reveal border border-stone-200 rounded-2xl p-6 bg-white hover:shadow-lg hover:-translate-y-1 transition group block">
    @endif
      @if($logo)
      <img src="{{ $logo }}" alt="{{ $o->singkatan }}" class="h-12 w-auto object-contain">
      @else
      <div class="w-11 h-11 rounded-xl bg-emerald-950 text-amber-400 flex items-center justify-center font-extrabold text-lg shrink-0">{{ mb_substr($o->singkatan, 0, 1) }}</div>
      @endif
      <h3 class="font-extrabold text-stone-900 text-[16px] mt-3 group-hover:text-orange-600 transition">{{ $o->singkatan }}</h3>
      @if(!empty($o->nama))<p class="text-[12px] font-bold text-amber-600 tracking-wide mt-0.5">{{ $o->nama }}</p>@endif
      @if(!empty($o->deskripsi_singkat))<p class="text-stone-500 text-[13.5px] mt-2 leading-relaxed">{{ $o->deskripsi_singkat }}</p>@endif
      @if($link)<span class="text-orange-600 font-extrabold text-[13px] mt-4 inline-block group-hover:translate-x-1 transition-transform">Detail →</span>@endif
    @if($link)
    </a>
    @else
    </div>
    @endif
    @endforeach
  </div>
  @endif
  <p class="text-[13px] text-stone-500 mt-5">Untuk bergabung dalam kepengurusan atau informasi lebih lanjut, hubungi sekretariat pondok melalui halaman <a href="{{ route('kontak') }}" class="text-orange-600 font-bold hover:underline">Kontak</a>.</p>
</div>
</div>
@endsection
