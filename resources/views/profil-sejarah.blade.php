@extends('layouts.app')
@section('title', 'Sejarah')
@section('content')
<div class="bg-white">
<div class="max-w-5xl mx-auto px-4 py-8 md:py-10">
  <p class="text-[12px] font-bold text-stone-400 tracking-wider"><a href="{{ route('home') }}" class="hover:text-orange-600 transition">Beranda</a> <span class="mx-1">/</span> <a href="{{ route('profil') }}" class="hover:text-orange-600 transition">Profil</a> <span class="mx-1">/</span> <span class="text-stone-600">Sejarah</span></p>
  <h1 class="font-extrabold text-stone-900 text-[26px] md:text-[30px] leading-tight mt-1.5">Sejarah</h1>
  <div class="w-16 h-1 bg-orange-400 rounded-full mt-3 mb-6"></div>

  <div class="reveal bg-white border border-stone-200 rounded-2xl p-6 md:p-8">
    <p class="text-[15px] leading-relaxed text-stone-600 text-justify">{{ \App\Models\Setting::get('profil_sejarah_lengkap', 'Berawal dari majelis taklim dan halaqah tahfidz kecil di Kauman, pondok berkembang menjadi lembaga pendidikan Islam terpadu: Madrasah Diniyah, MTs-MA, dan Ma\'had Aly.') }}</p>
    <p class="text-[15px] mt-4 leading-relaxed text-stone-600 text-justify">Kurikulum memadukan salaf (sorogan, bandongan, hafalan) dan khalaf (sekolah formal, bahasa, teknologi) dengan pengawasan musyrif 24 jam.</p>
  </div>
</div>
</div>
@endsection
