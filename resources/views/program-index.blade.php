@extends('layouts.app')
@section('title', 'Program Pendidikan')
@section('content')
<div class="bg-white">
<div class="max-w-7xl mx-auto px-4 py-8 md:py-10">
  <p class="text-[12px] font-bold text-stone-400 tracking-wider"><a href="{{ route('home') }}" class="hover:text-orange-600 transition">Beranda</a> <span class="mx-1">/</span> <span class="text-stone-600">Program</span></p>
  <h1 class="font-extrabold text-stone-900 text-[26px] md:text-[30px] leading-tight mt-1.5">Program Pendidikan</h1>
  <div class="w-16 h-1 bg-orange-400 rounded-full mt-3 mb-6"></div>

  <div class="grid md:grid-cols-3 gap-5">
  @forelse($programs as $p)
  <a href="{{ route('program.show', $p->slug) }}" class="reveal border border-stone-200 rounded-2xl p-6 bg-white hover:shadow-xl hover:-translate-y-1.5 transition group flex flex-col">
    <span class="self-start text-[11px] font-extrabold tracking-widest bg-amber-50 text-amber-700 px-3 py-1 rounded-full">{{ strtoupper($p->kategori ?? 'Program') }}</span>
    <h3 class="font-extrabold text-[17px] text-stone-900 mt-3 group-hover:text-orange-600 transition">{{ $p->nama }}</h3>
    <p class="text-[14px] text-stone-500 mt-2 leading-relaxed flex-1">{{ $p->deskripsi_singkat }}</p>
    <span class="text-orange-600 font-extrabold text-[13px] mt-4 group-hover:translate-x-1 transition-transform">Detail →</span>
  </a>
  @empty
  @foreach([
    ['📖','Tahfidzul Qur’an Reguler','Hafalan 30 juz, tasmi’ harian, munaqasyah semester.'],
    ['📚','Takhassus Kitab Kuning','Nahwu-Shorof, Fathul Mu’in, Tafsir Jalalain.'],
    ['🏫','MTs-MA Unggulan','Kurikulum Kemenag + diniyah + bilingual.'],
    ['🗣️','Bahasa Intensif','Arab & Inggris 6 bulan mahir percakapan.'],
    ['🎙️','Tahsin & Qira’ah','Perbaikan bacaan & seni baca Qur’an.'],
    ['💻','Life Skill & IT','Kaligrafi, desain, komputer, kewirausahaan.'],
  ] as $d)
  <div class="reveal border border-stone-200 rounded-2xl p-6 bg-white hover:shadow-xl hover:-translate-y-1.5 transition">
    <div class="w-11 h-11 rounded-xl bg-amber-50 flex items-center justify-center text-2xl">{{ $d[0] }}</div>
    <h3 class="font-extrabold text-[17px] text-stone-900 mt-3">{{ $d[1] }}</h3>
    <p class="text-[14px] text-stone-500 mt-2">{{ $d[2] }}</p>
  </div>
  @endforeach
  @endforelse
  </div>
</div>
</div>
@endsection
