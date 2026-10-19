@extends('layouts.alumni')

@section('title', 'Pendaftaran Alumni')
@section('meta_description', 'Masukkan NIK Anda untuk mendaftar atau melihat data alumni PP Kunuuzul Imam Kauman.')

@section('content')
<section class="bg-emerald-900 text-white">
  <div class="max-w-7xl mx-auto px-4 py-12 md:py-16 text-center">
    <p class="text-[11px] font-extrabold tracking-[0.3em] text-emerald-300">IKATAN ALUMNI</p>
    <h1 class="text-3xl md:text-5xl font-extrabold mt-3">Pendaftaran Alumni</h1>
    <p class="text-emerald-100/80 mt-3 text-sm md:text-base">Sensus Alumni PP Kunuuzul Imam Kauman</p>
    <p class="text-emerald-100/70 mt-4 max-w-xl mx-auto text-sm leading-relaxed">
      Masukkan NIK Anda untuk mendaftar atau melihat data alumni yang sudah tercatat.
    </p>

    <div class="mt-7">
      <p class="text-4xl md:text-5xl font-extrabold text-emerald-300" data-total-alumni="{{ $totalAlumni }}">{{ $totalAlumni }}</p>
      <p class="text-xs text-emerald-100/70 mt-1">Alumni sudah terdaftar</p>
    </div>
  </div>
</section>

<div class="max-w-xl mx-auto px-4 -mt-6 md:-mt-8 relative z-10">
  <div class="bg-white border border-stone-200 rounded-3xl p-6 md:p-8 shadow-lg shadow-stone-200/60">
    @include('alumni.partials.nik-check')
  </div>
</div>

<div class="max-w-2xl mx-auto px-4 py-10 md:py-12 text-center">
  <blockquote class="text-stone-700 italic text-sm md:text-base leading-relaxed">
    "Tidak ada yang namanya mantan santri. Sampai kapanpun, santri tetaplah santri."
  </blockquote>
  <p class="mt-5 text-stone-500 text-xs md:text-sm leading-relaxed">
    Pendataan ini bukan sekadar administrasi, melainkan jembatan silaturahmi: agar kita tetap
    saling mengenal, saling mendoakan, dan tetap tersambung dengan keluarga besar Pondok Pesantren
    Kunuuzul Imam Kauman — di mana pun kita mengabdi dan berkarya.
  </p>
</div>

@endsection
