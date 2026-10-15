@extends('layouts.app')
@section('title', 'Masyayikh Pondok')
@section('content')
<div class="bg-white">
<div class="max-w-5xl mx-auto px-4 py-8 md:py-10">
  <p class="text-[12px] font-bold text-stone-400 tracking-wider"><a href="{{ route('home') }}" class="hover:text-orange-600 transition">Beranda</a> <span class="mx-1">/</span> <a href="{{ route('profil') }}" class="hover:text-orange-600 transition">Profil</a> <span class="mx-1">/</span> <span class="text-stone-600">Masyayikh</span></p>
  <h1 class="font-extrabold text-stone-900 text-[26px] md:text-[30px] leading-tight mt-1.5">Masyayikh</h1>
  <div class="w-16 h-1 bg-orange-400 rounded-full mt-3 mb-6"></div>

  <div class="grid sm:grid-cols-2 gap-5">
    @php
      $listPengasuh = (isset($pengasuhs) && $pengasuhs->count()) ? $pengasuhs : collect([
        (object)['nama' => 'KH. Imam Kauman', 'jabatan' => 'Pengasuh — Pengampu Tafsir & Tasawuf'],
        (object)['nama' => 'Ust. Ahmad Fauzi, Lc.', 'jabatan' => 'Mudir Ma’had — Tahfidz'],
        (object)['nama' => 'Ustzh. Maryam Hafizhah', 'jabatan' => 'Musyrifah Putri — Tahsin'],
        (object)['nama' => 'Ust. Bilal Syah, M.Pd.', 'jabatan' => 'Kepala MTs-MA — Bahasa'],
      ]);
    @endphp
    @foreach($listPengasuh as $u)
    <div class="reveal border border-stone-200 rounded-2xl p-6 flex gap-4 items-start hover:shadow-lg hover:-translate-y-1 transition bg-white">
      <div class="w-14 h-14 rounded-full bg-emerald-950 text-amber-400 flex items-center justify-center font-extrabold text-xl shrink-0">{{ mb_substr($u->nama ?? $u[0], 0, 1) }}</div>
      <div><h2 class="font-extrabold text-stone-900 text-[16px]">{{ $u->nama ?? $u[0] }}</h2><p class="text-stone-500 text-[13.5px] mt-1">{{ $u->jabatan ?? $u[1] }}</p>@if(!empty($u->bio))<p class="text-stone-500 text-[13px] mt-1">{{ $u->bio }}</p>@endif</div>
    </div>
    @endforeach
  </div>
  <p class="text-[13px] text-stone-500 mt-5">Didukung 40+ ustadz/ustadzah lulusan pesantren & Timur Tengah.</p>
</div>
</div>
@endsection
