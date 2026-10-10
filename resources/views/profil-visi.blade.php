@extends('layouts.app')
@section('title', 'Visi & Misi')
@section('content')
<div class="bg-white">
<div class="max-w-5xl mx-auto px-4 py-8 md:py-10">
  <p class="text-[12px] font-bold text-stone-400 tracking-wider"><a href="{{ route('home') }}" class="hover:text-orange-600 transition">Beranda</a> <span class="mx-1">/</span> <a href="{{ route('profil') }}" class="hover:text-orange-600 transition">Profil</a> <span class="mx-1">/</span> <span class="text-stone-600">Visi & Misi</span></p>
  <h1 class="font-extrabold text-stone-900 text-[26px] md:text-[30px] leading-tight mt-1.5">Visi & Misi</h1>
  <div class="w-16 h-1 bg-orange-400 rounded-full mt-3 mb-6"></div>

  <div class="reveal border border-stone-200 rounded-2xl p-6 md:p-8 bg-amber-50/50">
    <h2 class="font-extrabold text-[15px] tracking-widest text-amber-600">VISI</h2>
    <p class="text-[17px] mt-2 leading-relaxed text-stone-800 italic">"{{ \App\Models\Setting::get('profil_visi', 'Menjadi pondok pesantren unggul dalam tahfidz, ilmu syar\'i, dan akhlak karimah menuju generasi rabbani.') }}"</p>
  </div>
  <div class="reveal border border-stone-200 rounded-2xl p-6 md:p-8 mt-5 bg-white">
    <h2 class="font-extrabold text-[15px] tracking-widest text-amber-600">MISI</h2>
    <ul class="text-[15px] mt-3 space-y-2.5 text-stone-700">
      @foreach(explode("\n", \App\Models\Setting::get('profil_misi', "1. Menyelenggarakan tahfidz 30 juz bersanad.\n2. Mengkaji kitab kuning secara intensif.")) as $m)
      @if(trim($m) !== '')<li class="flex gap-3"><span class="text-amber-500 font-extrabold">✓</span> {{ preg_replace('/^\d+[\.\)]\s*/', '', trim($m)) }}</li>@endif
      @endforeach
    </ul>
    <a href="{{ route('program.index') }}" class="inline-block mt-6 bg-emerald-950 hover:bg-emerald-800 text-white text-[12.5px] font-extrabold tracking-widest px-6 py-3 rounded-full transition">LIHAT PROGRAM →</a>
  </div>
</div>
</div>
@endsection
