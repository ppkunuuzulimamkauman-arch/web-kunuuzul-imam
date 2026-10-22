@extends('layouts.app')

@section('title', 'Portal SIAKAD')
@section('meta_description', 'Portal Sistem Informasi Akademik Pondok Pesantren Kunuuzul Imam Kauman.')

@section('content')
@php
  $mainUrl = request()->getScheme().'://'.config('pondok.domain');
@endphp
<section class="bg-emerald-900 text-white">
  <div class="max-w-5xl mx-auto px-4 py-16 md:py-24">
    <p class="text-[11px] font-extrabold tracking-[0.3em] text-emerald-300">SISTEM INFORMASI AKADEMIK</p>
    <h1 class="text-3xl md:text-5xl font-extrabold mt-3">Portal SIAKAD</h1>
    <p class="text-emerald-100/80 mt-4 max-w-2xl text-sm md:text-base leading-relaxed">
      Pusat data santri, tahun ajaran, kelas, dan perkembangan akademik Pondok Pesantren
      Kunuuzul Imam Kauman. Portal untuk santri dan wali santri sedang disiapkan.
    </p>
    <div class="mt-6 flex flex-wrap gap-3">
      <a href="{{ $mainUrl }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-white text-emerald-900 text-sm font-extrabold hover:bg-emerald-50 transition">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke Situs
      </a>
      <a href="{{ $mainUrl.'/admin/login' }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-xl border border-white/30 text-white text-sm font-extrabold hover:bg-white/10 transition">
        <i class="fa-solid fa-user-shield"></i> Masuk Admin
      </a>
    </div>
  </div>
</section>

<section class="max-w-5xl mx-auto px-4 py-12">
  <div class="grid gap-4 md:grid-cols-3">
    @foreach([
        ['icon' => 'fa-id-card', 'title' => 'Data Santri', 'desc' => 'Identitas induk yang menghubungkan data PSB, akademik, alumni, dan surat.'],
        ['icon' => 'fa-calendar-days', 'title' => 'Tahun Ajaran & Kelas', 'desc' => 'Pengelolaan periode akademik dan penempatan santri per kelas.'],
        ['icon' => 'fa-chart-line', 'title' => 'Nilai & Absensi', 'desc' => 'Fondasi pencatatan nilai dan kehadiran santri untuk tahap berikutnya.'],
    ] as $item)
      <div class="bg-white border border-stone-200 rounded-2xl p-5 shadow-sm">
        <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-800 flex items-center justify-center">
          <i class="fa-solid {{ $item['icon'] }}"></i>
        </div>
        <h2 class="mt-4 font-extrabold text-stone-900">{{ $item['title'] }}</h2>
        <p class="mt-2 text-sm text-stone-600 leading-relaxed">{{ $item['desc'] }}</p>
      </div>
    @endforeach
  </div>
</section>
@endsection
