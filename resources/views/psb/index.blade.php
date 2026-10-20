@extends('layouts.psb')

@section('title', 'Penerimaan Santri Baru ' . $tahunAjaran)
@section('meta_description', 'Pendaftaran santri baru PP Kunuuzul Imam Kauman tahun ajaran ' . $tahunAjaran . '. Tahfidz 30 juz, kajian kitab kuning, sekolah formal MTs–MA, dan pembinaan akhlak 24 jam. Daftar online, cek syarat dan alurnya.')

@push('head')
<style>
  /* ===== FAQ ===== */
  summary::-webkit-details-marker { display: none; }
  summary { list-style: none; }

  /* ===== Motion PSB (navbar sengaja tidak dianimasikan) ===== */
  @keyframes psbUp { from { opacity: 0; transform: translateY(26px); } to { opacity: 1; transform: none; } }
  @keyframes psbKenBurns { from { transform: scale(1.02); } to { transform: scale(1.14); } }
  @keyframes psbFloatY { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-14px); } }
  @keyframes psbFloatXY { 0%, 100% { transform: translate(0, 0); } 50% { transform: translate(12px, -20px); } }
  @keyframes psbPulseDot { 0%, 100% { box-shadow: 0 0 0 0 rgba(252, 211, 77, .5); } 50% { box-shadow: 0 0 0 7px rgba(252, 211, 77, 0); } }
  @keyframes psbNudge { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(6px); } }
  @keyframes psbMarquee { from { transform: translateX(0); } to { transform: translateX(-50%); } }

  .psb-enter { animation: psbUp .85s cubic-bezier(.22, 1, .36, 1) both; animation-delay: var(--d, 0s); }
  .psb-hero-img { animation: psbKenBurns 26s ease-in-out infinite alternate; will-change: transform; }
  .psb-float { animation: psbFloatY 8s ease-in-out infinite; }
  .psb-float-slow { animation: psbFloatXY 12s ease-in-out infinite; }
  .psb-live-dot { animation: psbPulseDot 2.4s ease-out infinite; }

  .psb-ornament { position: absolute; pointer-events: none; }
  .psb-ornament-star { top: 2.5rem; right: -2rem; font-size: 14rem; line-height: 1; color: rgba(255, 255, 255, .05); }
  .psb-ornament-orb { bottom: -7rem; right: 20%; width: 18rem; height: 18rem; border-radius: 9999px; background: rgba(252, 211, 77, .10); filter: blur(52px); }
  .psb-ornament-dot { bottom: 2.5rem; right: 2.5rem; width: .6rem; height: .6rem; border-radius: 9999px; background: rgba(252, 211, 77, .55); }

  .psb-scroll-cue { position: absolute; left: 50%; bottom: 1.25rem; transform: translateX(-50%); display: none; align-items: center; gap: .45rem; font-size: 9px; font-weight: 800; letter-spacing: .28em; text-transform: uppercase; color: rgba(255, 255, 255, .5); }
  .psb-scroll-cue i { animation: psbNudge 1.9s ease-in-out infinite; }

  .psb-marquee { overflow: hidden; border-top: 1px solid rgba(180, 83, 9, .25); border-bottom: 1px solid rgba(180, 83, 9, .25); background: #fbbf24; color: #064e3b; }
  .psb-marquee-track { display: flex; width: max-content; animation: psbMarquee 34s linear infinite; }
  .psb-marquee:hover .psb-marquee-track { animation-play-state: paused; }
  .psb-marquee-row { display: flex; align-items: center; gap: 2rem; padding: .7rem 2rem .7rem 0; font-size: 10px; font-weight: 800; letter-spacing: .22em; text-transform: uppercase; white-space: nowrap; }
  .psb-marquee-row i { font-size: 8px; color: rgba(146, 64, 14, .65); }

  .psb-kicker { position: relative; display: inline-block; }
  .psb-kicker::after { content: ""; position: absolute; left: 0; bottom: -.45rem; height: 2px; width: 100%; transform: scaleX(0); transform-origin: left; background: linear-gradient(90deg, #f59e0b, rgba(245, 158, 11, 0)); transition: transform .8s cubic-bezier(.22, 1, .36, 1) .2s; }
  .reveal.in .psb-kicker::after { transform: scaleX(1); }

  .psb-card { transition: transform .35s cubic-bezier(.22, 1, .36, 1), box-shadow .35s ease, border-color .35s ease; }
  .psb-card:hover { transform: translateY(-5px); box-shadow: 0 22px 40px -24px rgba(6, 78, 59, .45); border-color: #a7f3d0; }
  .psb-ic { transition: transform .35s ease, background-color .35s ease, color .35s ease; }
  .psb-card:hover .psb-ic, .lift:hover .psb-ic { transform: translateY(-2px) rotate(-6deg); }

  .psb-img-wrap { overflow: hidden; }
  .psb-img { transition: transform .7s cubic-bezier(.22, 1, .36, 1); }
  .psb-img-wrap:hover .psb-img { transform: scale(1.05); }

  .psb-cta { position: relative; overflow: hidden; }
  .psb-cta::after { content: ""; position: absolute; top: 0; bottom: 0; left: -70%; width: 45%; transform: skewX(-18deg); background: linear-gradient(90deg, transparent, rgba(255, 255, 255, .45), transparent); transition: left .65s ease; pointer-events: none; }
  .psb-cta:hover::after { left: 130%; }

  .js .reveal[data-delay="5"] { transition-delay: .4s; }
  .js .reveal[data-delay="6"] { transition-delay: .48s; }

  @media (min-width: 768px) {
    .psb-scroll-cue { display: flex; }
  }

  @media (max-width: 767px) {
    .psb-ornament-star, .psb-ornament-dot { display: none; }
  }

  @media (prefers-reduced-motion: reduce) {
    .psb-enter, .psb-hero-img, .psb-float, .psb-float-slow, .psb-live-dot, .psb-marquee-track, .psb-scroll-cue i { animation: none !important; }
    .psb-marquee { overflow-x: auto; }
    .psb-marquee-row { padding-right: 1rem; }
    .psb-marquee-row + .psb-marquee-row { display: none; }
    .psb-card, .psb-ic, .psb-img, .psb-cta::after, .psb-kicker::after { transition: none !important; }
    .psb-kicker::after { transform: scaleX(1); }
    .psb-card:hover { transform: none; }
    .psb-cta:hover::after { left: -70%; }
  }
</style>
@php
  // chr(64) agar '@context' tidak dibaca sebagai Blade directive.
  $at = chr(64);
  $faq = [
      [
          'q' => 'Bagaimana cara mendaftar sebagai santri baru?',
          'a' => 'Pendaftaran dilakukan melalui formulir online di situs utama pondok. Setelah formulir terkirim, Anda akan menerima nomor pendaftaran yang bisa dipakai untuk mengecek status.',
      ],
      [
          'q' => 'Berkas apa saja yang harus disiapkan?',
          'a' => 'Kartu Keluarga (KK) wajib diunggah. Ijazah atau Surat Keterangan Lulus (SKL) dan KIP bersifat opsional. Format berkas PDF, DOC, DOCX, JPG, JPEG, atau PNG dengan ukuran maksimal 4 MB per berkas.',
      ],
      [
          'q' => 'Apakah ada biaya pendidikan?',
          'a' => $biayaInfo !== '' ? $biayaInfo : 'Informasi biaya pendidikan dapat ditanyakan langsung kepada panitia PSB.',
      ],
      [
          'q' => 'Bagaimana cara mengecek status pendaftaran?',
          'a' => 'Buka halaman Cek Status Pendaftaran, lalu masukkan nomor pendaftaran yang Anda terima setelah mengirim formulir.',
      ],
      [
          'q' => 'Apakah harus datang langsung ke pondok untuk mendaftar?',
          'a' => 'Tidak. Pendaftaran dimulai secara online. Panitia akan menghubungi nomor WhatsApp yang didaftarkan untuk verifikasi data dan berkas.',
      ],
  ];
@endphp
<script type="application/ld+json">
{!! json_encode([
    $at.'context' => 'https://schema.org',
    $at.'type' => 'FAQPage',
    'mainEntity' => collect($faq)->map(fn ($item) => [
        $at.'type' => 'Question',
        'name' => $item['q'],
        'acceptedAnswer' => [$at.'type' => 'Answer', 'text' => $item['a']],
    ])->all(),
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endpush

@section('content')
{{-- ===== HERO ===== --}}
<section class="relative isolate overflow-hidden bg-primary-900 text-white">
  <img src="{{ asset('images/hero/slide-2.jpg') }}" alt="Santri PP Kunuuzul Imam Kauman bersama ustadz" fetchpriority="high" class="psb-hero-img absolute inset-0 -z-10 h-full w-full object-cover object-center opacity-40">
  <div class="absolute inset-0 -z-10 bg-gradient-to-r from-primary-900 via-primary-900/95 to-primary-900/40"></div>
  <div class="pointer-events-none absolute inset-0 -z-10 overflow-hidden" aria-hidden="true">
    <i class="fa-solid fa-star-and-crescent psb-ornament psb-ornament-star psb-float"></i>
    <span class="psb-ornament psb-ornament-orb psb-float-slow"></span>
    <span class="psb-ornament psb-ornament-dot psb-float"></span>
  </div>

  <div class="max-w-7xl mx-auto px-4 py-16 md:py-24">
    <div class="max-w-2xl">
      <div class="psb-enter flex flex-wrap items-center gap-2" style="--d:.05s">
        <span class="inline-flex items-center gap-2 rounded-full bg-amber-400 px-4 py-1.5 text-[11px] font-extrabold tracking-[0.18em] text-primary-900">
          PSB {{ $tahunAjaran }}
        </span>
        @if($status !== '')
          <span class="inline-flex items-center gap-2 rounded-full border border-white/30 bg-white/10 px-3.5 py-1.5 text-[11px] font-bold tracking-[0.14em] text-amber-200">
            <span class="psb-live-dot w-2 h-2 rounded-full bg-amber-300"></span> {{ mb_strtoupper($status) }}
          </span>
        @endif
      </div>

      <h1 class="psb-enter mt-6 text-3xl sm:text-4xl md:text-5xl font-extrabold leading-tight" style="--d:.15s">
        Penerimaan Santri Baru <span class="text-amber-300">{{ $tahunAjaran }}</span>
      </h1>

      <p class="psb-enter mt-5 max-w-xl text-sm md:text-base leading-relaxed text-emerald-50/90" style="--d:.25s">
        Bergabunglah bersama santri Kunuuzul Imam Kauman: menghafal Al-Qur'an 30 juz,
        mengkaji kitab kuning, menempuh pendidikan formal MTs–MA, dan dibina akhlaknya
        24 jam di lingkungan pesantren.
      </p>

      <div class="psb-enter mt-8 flex flex-col sm:flex-row gap-3" style="--d:.35s">
        <a href="{{ $mainUrl }}/ppdb" class="psb-cta inline-flex items-center justify-center gap-2 rounded-xl bg-amber-400 px-6 py-3.5 text-sm font-extrabold text-primary-900 transition hover:bg-amber-300">
          <i class="fa-solid fa-pen-to-square"></i> Daftar Sekarang
        </a>
        <a href="{{ $mainUrl }}/ppdb/cek" class="inline-flex items-center justify-center gap-2 rounded-xl border border-white/40 px-6 py-3.5 text-sm font-bold text-white transition hover:bg-white/10">
          <i class="fa-solid fa-magnifying-glass"></i> Cek Status Pendaftaran
        </a>
      </div>

      <dl class="psb-enter mt-10 grid max-w-xl grid-cols-2 gap-x-6 gap-y-5 border-t border-white/15 pt-6 sm:grid-cols-4" style="--d:.45s">
        <div>
          <dt class="sr-only">Target hafalan</dt>
          <dd class="text-2xl font-extrabold text-amber-300"><span data-count="30">30</span> Juz</dd>
          <dd class="mt-0.5 text-[11px] font-bold uppercase tracking-wider text-emerald-100/70">Target Tahfidz</dd>
        </div>
        <div>
          <dt class="sr-only">Jenjang formal</dt>
          <dd class="text-2xl font-extrabold text-amber-300">MTs–MA</dd>
          <dd class="mt-0.5 text-[11px] font-bold uppercase tracking-wider text-emerald-100/70">Sekolah Formal</dd>
        </div>
        <div>
          <dt class="sr-only">Pembinaan</dt>
          <dd class="text-2xl font-extrabold text-amber-300"><span data-count="24">24</span> Jam</dd>
          <dd class="mt-0.5 text-[11px] font-bold uppercase tracking-wider text-emerald-100/70">Pembinaan Asrama</dd>
        </div>
        <div>
          <dt class="sr-only">Jumlah santri</dt>
          <dd class="text-2xl font-extrabold text-amber-300"><span data-count="500">500</span>+</dd>
          <dd class="mt-0.5 text-[11px] font-bold uppercase tracking-wider text-emerald-100/70">Santri Mukim</dd>
        </div>
      </dl>
    </div>
  </div>

  <a href="#pilar" class="psb-scroll-cue" aria-label="Gulir ke bagian berikutnya">
    Gulir <i class="fa-solid fa-chevron-down"></i>
  </a>
</section>

{{-- ===== TICKER ===== --}}
<div class="psb-marquee" aria-hidden="true">
  <div class="psb-marquee-track">
    @for($copy = 0; $copy < 2; $copy++)
      <div class="psb-marquee-row">
        @foreach(['Tahfidz 30 Juz', 'Kitab Kuning', 'MTs – MA', 'Pembinaan 24 Jam', 'Bahasa Arab & Inggris', 'Pendaftaran Online'] as $word)
          <span>{{ $word }}</span><i class="fa-solid fa-star-of-life"></i>
        @endforeach
      </div>
    @endfor
  </div>
</div>

{{-- ===== EMPAT PILAR ===== --}}
<section id="pilar" class="max-w-7xl mx-auto px-4 py-16 md:py-20">
  <div class="grid items-center gap-12 lg:grid-cols-2 lg:gap-16">
    <div class="relative reveal">
      <div class="psb-img-wrap rounded-3xl shadow-xl">
        <img src="{{ asset('images/hero/slide-1.jpg') }}" alt="Santri menghafal Al-Qur'an di lingkungan pondok" loading="lazy" class="psb-img h-[320px] w-full object-cover md:h-[420px]">
      </div>
      <div class="absolute -bottom-6 left-5 right-5 rounded-2xl border border-stone-200 bg-white p-4 shadow-xl md:left-8 md:right-auto md:w-72">
        <p class="text-[10px] font-extrabold tracking-[0.2em] text-emerald-700">LINGKUNGAN PESANTREN</p>
        <p class="mt-1 text-sm font-bold leading-snug text-stone-900">Belajar, menghafal, dan hidup bersama dalam bimbingan musyrif.</p>
      </div>
    </div>

    <div class="reveal" data-delay="1">
      <p class="psb-kicker text-[11px] font-extrabold tracking-[0.3em] text-emerald-700">MENGAPA KUNUUZUL IMAM?</p>
      <h2 class="mt-2 text-2xl font-extrabold text-stone-900 md:text-3xl">Satu pondok, empat pilar pembinaan</h2>
      <p class="mt-3 text-sm leading-relaxed text-stone-600">
        Kurikulum salaf dan khalaf dipadukan: hafalan Al-Qur'an, kajian kitab kuning,
        pendidikan formal, serta pembinaan adab dan kemandirian sepanjang hari.
      </p>

      <div class="mt-7 grid gap-4 sm:grid-cols-2">
        <div class="rounded-2xl border border-stone-200 bg-white p-5 lift">
          <div class="psb-ic flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700"><i class="fa-solid fa-book-open"></i></div>
          <h3 class="mt-3 font-extrabold text-stone-900">Tahfidz 30 Juz</h3>
          <p class="mt-1 text-[13px] leading-relaxed text-stone-500">Setoran hafalan harian dengan target dan murojaah yang terarah.</p>
        </div>
        <div class="rounded-2xl border border-stone-200 bg-white p-5 lift">
          <div class="psb-ic flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50 text-amber-600"><i class="fa-solid fa-feather-pointed"></i></div>
          <h3 class="mt-3 font-extrabold text-stone-900">Kitab Kuning</h3>
          <p class="mt-1 text-[13px] leading-relaxed text-stone-500">Sorogan dan bandongan kitab salaf bersama para masyayikh.</p>
        </div>
        <div class="rounded-2xl border border-stone-200 bg-white p-5 lift">
          <div class="psb-ic flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700"><i class="fa-solid fa-school"></i></div>
          <h3 class="mt-3 font-extrabold text-stone-900">Sekolah Formal</h3>
          <p class="mt-1 text-[13px] leading-relaxed text-stone-500">Jenjang MTs dan MA dengan kurikulum nasional.</p>
        </div>
        <div class="rounded-2xl border border-stone-200 bg-white p-5 lift">
          <div class="psb-ic flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50 text-amber-600"><i class="fa-solid fa-people-roof"></i></div>
          <h3 class="mt-3 font-extrabold text-stone-900">Pembinaan 24 Jam</h3>
          <p class="mt-1 text-[13px] leading-relaxed text-stone-500">Asrama putra-putri dengan pengawasan musyrif dan pembiasaan adab.</p>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ===== ALUR PENDAFTARAN ===== --}}
<section id="alur" class="border-y border-stone-200 bg-white">
  <div class="max-w-7xl mx-auto px-4 py-16 md:py-20">
    <div class="reveal max-w-2xl">
      <p class="psb-kicker text-[11px] font-extrabold tracking-[0.3em] text-emerald-700">ALUR PENDAFTARAN</p>
      <h2 class="mt-2 text-2xl font-extrabold text-stone-900 md:text-3xl">Empat langkah, selesai dari rumah</h2>
      <p class="mt-3 text-sm leading-relaxed text-stone-600">Seluruh proses dimulai secara online. Panitia akan memverifikasi data dan berkas melalui WhatsApp.</p>
    </div>

    <ol class="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
      @php
        $langkah = [
            ['Isi Formulir', 'Lengkapi data santri, orang tua/wali, dan pilihan jenjang.', 'fa-pen-to-square'],
            ['Unggah Berkas', 'Unggah Kartu Keluarga (wajib) serta ijazah/SKL dan KIP bila ada.', 'fa-file-arrow-up'],
            ['Verifikasi Panitia', 'Panitia menghubungi nomor WhatsApp yang didaftarkan.', 'fa-comments'],
            ['Cek Status', 'Pantau status dengan nomor pendaftaran yang Anda terima.', 'fa-circle-check'],
        ];
      @endphp
      @foreach($langkah as $i => $step)
        <li class="psb-card reveal relative rounded-2xl border border-stone-200 bg-stone-50 p-5" data-delay="{{ $i + 1 }}">
          <div class="flex items-center justify-between">
            <span class="psb-ic flex h-11 w-11 items-center justify-center rounded-xl bg-primary-900 text-lg text-amber-300">
              <i class="fa-solid {{ $step[2] }}"></i>
            </span>
            <span class="text-3xl font-extrabold text-stone-200">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
          </div>
          <h3 class="mt-4 font-extrabold text-stone-900">{{ $step[0] }}</h3>
          <p class="mt-1 text-[13px] leading-relaxed text-stone-500">{{ $step[1] }}</p>
        </li>
      @endforeach
    </ol>
  </div>
</section>

{{-- ===== JENJANG & SYARAT ===== --}}
<section id="syarat" class="max-w-7xl mx-auto px-4 py-16 md:py-20">
  <div class="grid gap-8 lg:grid-cols-5">
    <div class="lg:col-span-3">
      <div class="reveal">
        <p class="psb-kicker text-[11px] font-extrabold tracking-[0.3em] text-emerald-700">JENJANG PENDIDIKAN</p>
        <h2 class="mt-2 text-2xl font-extrabold text-stone-900 md:text-3xl">Pilih jenjang yang sesuai</h2>
        <p class="mt-3 max-w-xl text-sm leading-relaxed text-stone-600">Pilihan jenjang disampaikan saat mengisi formulir. Panitia akan membantu menyesuaikan program bila diperlukan.</p>
      </div>

      <div class="mt-6 space-y-4">
        <div class="psb-card reveal flex items-start gap-4 rounded-2xl border border-stone-200 bg-white p-5" data-delay="1">
          <div class="psb-ic flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-lg text-emerald-700"><i class="fa-solid fa-book"></i></div>
          <div>
            <h3 class="font-extrabold text-stone-900">SMP Kunuuzul Imam Kauman</h3>
            <p class="mt-1 text-[13px] leading-relaxed text-stone-500">Pendidikan formal jenjang menengah pertama, dipadukan dengan tahfidz dan madrasah diniyah.</p>
          </div>
        </div>
        <div class="psb-card reveal flex items-start gap-4 rounded-2xl border border-stone-200 bg-white p-5" data-delay="2">
          <div class="psb-ic flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-lg text-amber-600"><i class="fa-solid fa-graduation-cap"></i></div>
          <div>
            <h3 class="font-extrabold text-stone-900">MA Kunuuzul Imam Kauman</h3>
            <p class="mt-1 text-[13px] leading-relaxed text-stone-500">Pendidikan formal jenjang menengah atas dengan pendalaman kitab kuning dan pembinaan tahfidz.</p>
          </div>
        </div>
        <div class="psb-card reveal flex items-start gap-4 rounded-2xl border border-stone-200 bg-white p-5" data-delay="3">
          <div class="psb-ic flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-lg text-emerald-700"><i class="fa-solid fa-mosque"></i></div>
          <div>
            <h3 class="font-extrabold text-stone-900">Kuliah / Program Lanjutan</h3>
            <p class="mt-1 text-[13px] leading-relaxed text-stone-500">Untuk lulusan MA yang melanjutkan studi atau pendalaman kitab. Sampaikan pilihan program pada formulir.</p>
          </div>
        </div>
      </div>
    </div>

    <div class="lg:col-span-2">
      <div class="reveal h-full rounded-3xl border border-stone-200 bg-white p-6 shadow-sm md:p-7" data-delay="2">
        <div class="flex items-center gap-3">
          <div class="psb-ic flex h-11 w-11 items-center justify-center rounded-xl bg-primary-900 text-amber-300"><i class="fa-solid fa-folder-open"></i></div>
          <div>
            <p class="text-[10px] font-extrabold tracking-[0.2em] text-stone-400">SIAPKAN SEBELUM DAFTAR</p>
            <h2 class="text-lg font-extrabold text-stone-900">Syarat &amp; berkas</h2>
          </div>
        </div>

        <ul class="mt-5 space-y-3">
          @forelse($syarat as $item)
            <li class="flex items-start gap-3 text-[13px] leading-relaxed text-stone-600">
              <i class="fa-solid fa-circle-check mt-0.5 text-emerald-600"></i>
              <span>{{ $item }}</span>
            </li>
          @empty
            <li class="text-[13px] text-stone-500">Informasi persyaratan dapat ditanyakan kepada panitia.</li>
          @endforelse
        </ul>

        <p class="mt-5 rounded-xl bg-stone-50 p-3 text-[12px] leading-relaxed text-stone-500">
          <i class="fa-solid fa-circle-info mr-1"></i>
          Berkas diunggah saat mengisi formulir online (PDF/DOC/gambar, maks. 4 MB per berkas).
        </p>

        <a href="{{ $mainUrl }}/ppdb" class="psb-cta mt-5 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-primary-900 px-5 py-3.5 text-[13px] font-extrabold text-white transition hover:bg-primary-800">
          Mulai Daftar <i class="fa-solid fa-arrow-right"></i>
        </a>
      </div>
    </div>
  </div>
</section>

{{-- ===== JADWAL ===== --}}
<section id="jadwal" class="border-y border-stone-200 bg-white">
  <div class="max-w-7xl mx-auto px-4 py-16 md:py-20">
    <div class="reveal max-w-2xl">
      <p class="psb-kicker text-[11px] font-extrabold tracking-[0.3em] text-emerald-700">JADWAL PSB</p>
      <h2 class="mt-2 text-2xl font-extrabold text-stone-900 md:text-3xl">Gelombang pendaftaran</h2>
      <p class="mt-3 text-sm leading-relaxed text-stone-600">Pantau jadwal terbaru di halaman ini atau melalui kanal resmi pondok.</p>
    </div>

    @if(count($gelombang) > 0)
      <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @foreach($gelombang as $g)
          <div class="psb-card reveal rounded-2xl border border-stone-200 bg-stone-50 p-5" data-delay="{{ ($loop->index % 3) + 1 }}">
            <p class="text-[10px] font-extrabold tracking-[0.2em] text-emerald-700">GELOMBANG</p>
            <h3 class="mt-1 text-lg font-extrabold text-stone-900">{{ $g['nama'] }}</h3>
            @if($g['periode'] !== '')
              <p class="mt-2 flex items-start gap-2 text-[13px] text-stone-600">
                <i class="fa-regular fa-calendar mt-0.5 text-stone-400"></i> <span>{{ $g['periode'] }}</span>
              </p>
            @endif
            @if($g['catatan'] !== '')
              <p class="mt-2 text-[12px] leading-relaxed text-stone-500">{{ $g['catatan'] }}</p>
            @endif
          </div>
        @endforeach
      </div>
    @else
      <div class="reveal mt-8 flex flex-col items-start justify-between gap-4 rounded-2xl border border-dashed border-stone-300 bg-stone-50 p-6 sm:flex-row sm:items-center">
        <div class="flex items-start gap-3">
          <i class="fa-regular fa-calendar-days mt-0.5 text-lg text-stone-400"></i>
          <p class="text-sm leading-relaxed text-stone-600">
            Jadwal gelombang belum diumumkan. Pendaftaran tetap dapat dilakukan online,
            atau tanyakan jadwal terbaru kepada panitia.
          </p>
        </div>
        @if($waUrl)
          <a href="{{ $waUrl }}" target="_blank" rel="noopener" class="inline-flex shrink-0 items-center gap-2 rounded-xl border border-emerald-700 px-5 py-2.5 text-[13px] font-extrabold text-emerald-800 transition hover:bg-emerald-50">
            <i class="fa-brands fa-whatsapp"></i> Tanya Panitia
          </a>
        @else
          <a href="{{ $mainUrl }}/kontak" class="inline-flex shrink-0 items-center gap-2 rounded-xl border border-emerald-700 px-5 py-2.5 text-[13px] font-extrabold text-emerald-800 transition hover:bg-emerald-50">
            <i class="fa-solid fa-envelope"></i> Hubungi Panitia
          </a>
        @endif
      </div>
    @endif
  </div>
</section>

{{-- ===== FAQ ===== --}}
<section id="faq" class="max-w-3xl mx-auto px-4 py-16 md:py-20">
  <div class="reveal text-center">
    <p class="psb-kicker text-[11px] font-extrabold tracking-[0.3em] text-emerald-700">FAQ</p>
    <h2 class="mt-2 text-2xl font-extrabold text-stone-900 md:text-3xl">Pertanyaan yang sering diajukan</h2>
  </div>

  <div class="mt-8 space-y-3">
    @foreach($faq as $item)
      <details class="group reveal rounded-2xl border border-stone-200 bg-white p-5 transition hover:border-emerald-200" data-delay="{{ ($loop->index % 4) + 1 }}">
        <summary class="flex cursor-pointer items-center justify-between gap-4 font-bold text-stone-900">
          <span>{{ $item['q'] }}</span>
          <i class="fa-solid fa-plus shrink-0 text-sm text-emerald-700 transition group-open:rotate-45"></i>
        </summary>
        <p class="mt-3 text-sm leading-relaxed text-stone-600">{{ $item['a'] }}</p>
      </details>
    @endforeach
  </div>
</section>
@endsection
