@extends('layouts.app')
@section('title', 'Lembaga Pendidikan')
@section('content')
<div class="bg-white">
<div class="max-w-7xl mx-auto px-4 py-8 md:py-10">
  <p class="text-[12px] font-bold text-stone-400 tracking-wider"><a href="{{ route('home') }}" class="hover:text-orange-600 transition">Beranda</a> <span class="mx-1">/</span> <span class="text-stone-600">Lembaga</span></p>
  <h1 class="font-extrabold text-stone-900 text-[26px] md:text-[30px] leading-tight mt-1.5">Lembaga</h1>
  <div class="w-16 h-1 bg-orange-400 rounded-full mt-3 mb-6"></div>

  <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
    @php
      $listLembaga = (isset($lembagas) && $lembagas->count()) ? $lembagas : collect([
        (object)['ikon' => '🕌', 'nama' => 'MADRASAH DINIYAH TASHWIRUL AFKAR', 'deskripsi' => 'Madrasah Diniyah: kajian kitab kuning — Nahwu, Shorof, Fiqh, Tauhid, dan Tafsir.'],
        (object)['ikon' => '🎓', 'nama' => 'MA Kunuuzul Imam', 'deskripsi' => 'Madrasah Aliyah: pendidikan formal setingkat SMA, bilingual Arab-Inggris, dan pembinaan olimpiade.'],
        (object)['ikon' => '🏫', 'nama' => 'SMP Kunuuzul Imam', 'deskripsi' => 'Sekolah Menengah Pertama: kurikulum nasional + kepesantrenan dan tahfidz harian.'],
        (object)['ikon' => '📖', 'nama' => 'Tahfidzul Qur’an', 'deskripsi' => 'Hafalan 30 juz bersanad, tasmi harian, dan munaqasyah tiap semester.'],
        (object)['ikon' => '🗣️', 'nama' => 'Bahasa Intensif', 'deskripsi' => 'Program Arab & Inggris dengan pembiasaan 24 jam.'],
        (object)['ikon' => '🎙️', 'nama' => 'Tahsin & Qira’ah', 'deskripsi' => 'Perbaikan makhraj, tajwid, dan seni baca Al-Qur’an.'],
      ]);
    @endphp
    @foreach($listLembaga as $l)
    @php $url = $l->url ?? (str_contains(strtolower($l->nama ?? $l[1] ?? ''), 'ma kunuuzul') ? 'https://makunuuzulimamkauman.sch.id/' : null); @endphp
    <div class="reveal border border-stone-200 rounded-2xl p-6 hover:shadow-lg hover:-translate-y-1 transition bg-white group">
      <div class="w-12 h-12 rounded-2xl bg-amber-50 flex items-center justify-center text-2xl">{{ $l->ikon ?? $l[0] ?? '🏫' }}</div>
      <h2 class="font-extrabold text-stone-900 text-[17px] mt-4 group-hover:text-orange-600 transition uppercase">{{ $l->nama ?? $l[1] }}</h2>
      <p class="text-stone-500 text-[14px] leading-relaxed mt-2">{{ $l->deskripsi ?? $l[2] }}</p>
      @if($url)<a href="{{ $url }}" target="_blank" rel="noopener" class="inline-block mt-3 text-[13px] font-extrabold text-emerald-700 hover:translate-x-1 transition-transform">Kunjungi Website</a>@endif
    </div>
    @endforeach
  </div>
</div>
</div>
@endsection
