@extends('layouts.app')
@section('title', 'Sejarah Pondok')
@section('content')
<div class="bg-white">
<div class="max-w-5xl mx-auto px-4 py-8 md:py-10">
  <p class="text-[12px] font-bold text-stone-400 tracking-wider"><a href="{{ route('home') }}" class="hover:text-orange-600 transition">Beranda</a> <span class="mx-1">/</span> <a href="{{ route('profil') }}" class="hover:text-orange-600 transition">Profil</a> <span class="mx-1">/</span> <span class="text-stone-600">Sejarah</span></p>
  <h1 class="font-extrabold text-stone-900 text-[26px] md:text-[30px] leading-tight mt-1.5">Sejarah</h1>
  <div class="w-16 h-1 bg-orange-400 rounded-full mt-3 mb-6"></div>

  <div class="reveal bg-white border border-stone-200 rounded-2xl p-6 md:p-8">
    @php
      $S = \App\Models\Setting::class;
      $sampul = trim((string) $S::get('profil_sejarah_gambar', ''));
      $parafs = preg_split('/\R{2,}/', trim((string) $S::get('profil_sejarah_lengkap', 'Berawal dari majelis taklim dan halaqah tahfidz kecil di Kauman.')));
      $parafs = array_values(array_filter(array_map('trim', $parafs), fn($p) => $p !== ''));
    @endphp
    @if($sampul !== '')
      <img src="{{ str_starts_with($sampul, 'http') || str_starts_with($sampul, '/') ? $sampul : '/storage/' . ltrim($sampul, '/') }}" alt="Sejarah Pondok" class="w-full max-h-[420px] object-cover rounded-xl mb-6" loading="lazy" onerror="this.style.display='none'">
    @endif
    @forelse($parafs as $p)
      <p class="text-[15px] leading-relaxed text-stone-600 text-justify mb-4 last:mb-0">{!! nl2br(e($p)) !!}</p>
    @empty
      <p class="text-[15px] leading-relaxed text-stone-600 text-justify">Sejarah pondok segera dilengkapi.</p>
    @endforelse
    <p class="text-[15px] mt-4 leading-relaxed text-stone-600 text-justify">Kurikulum memadukan salaf (sorogan, bandongan, hafalan) dan khalaf (sekolah formal, bahasa, teknologi) dengan pengawasan musyrif 24 jam.</p>
  </div>
</div>
</div>
@endsection
