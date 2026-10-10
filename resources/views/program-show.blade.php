@extends('layouts.app')
@section('title', $program->nama)
@section('content')
<div class="bg-white">
<div class="max-w-4xl mx-auto px-4 py-8 md:py-10">
  <a href="{{ route('program.index') }}" class="text-[13px] font-extrabold text-orange-600 hover:-translate-x-1 transition-transform inline-block">← Semua Program</a>
  <h1 class="font-extrabold text-stone-900 text-[26px] md:text-[32px] leading-tight mt-2">{{ $program->nama }}</h1>
  <p class="text-[13px] text-stone-500 mt-2"><span class="bg-amber-50 text-amber-700 font-bold px-2.5 py-1 rounded-lg">{{ ucfirst($program->kategori ?? 'Program') }}</span> <span class="ml-2">{{ $program->deskripsi_singkat }}</span></p>
  <div class="w-16 h-1 bg-orange-400 rounded-full mt-4 mb-6"></div>
  <div class="reveal border border-stone-200 rounded-2xl p-6 md:p-8 text-[15px] leading-relaxed text-stone-700">{!! nl2br(e($program->deskripsi ?? $program->deskripsi_singkat)) !!}</div>
  <a href="{{ route('ppdb.form') }}" class="reveal block text-center mt-6 bg-emerald-950 hover:bg-emerald-800 text-white py-3.5 rounded-2xl font-extrabold text-[13px] tracking-widest transition">DAFTAR PROGRAM INI VIA PSB →</a>
</div>
</div>
@endsection
