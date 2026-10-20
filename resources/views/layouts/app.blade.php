<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@php
  $__siteName = 'PP Kunuuzul Imam Kauman';
  $__pageTitle = trim($__env->yieldContent('title'));
  echo ($__pageTitle === '' || $__pageTitle === $__siteName) ? $__siteName : $__pageTitle . ' | ' . $__siteName;
@endphp</title>
@php
  $metaDesc = trim($__env->yieldContent('meta_description')) ?: \App\Models\Setting::get('site_deskripsi', "Pondok Pesantren Kunuuzul Imam Kauman — Tahfidzul Qur'an 30 juz, kajian kitab kuning, sekolah formal MTs-MA, dan tarbiyah akhlak 24 jam.");
  $metaImg = trim($__env->yieldContent('meta_image')) ?: asset('images/tirai.jpg');
  $metaUrl = url()->current();
@endphp
<meta name="description" content="{{ $metaDesc }}">
<link rel="canonical" href="{{ $metaUrl }}">
<meta name="theme-color" content="#064e3b">
<link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=2">
<link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=2">
<link rel="apple-touch-icon" href="{{ asset('images/logo-pondok.png') }}">
<meta property="og:site_name" content="PP Kunuuzul Imam Kauman">
<meta property="og:locale" content="id_ID">
<meta property="og:type" content="website">
<meta property="og:title" content="@yield('title', 'PP Kunuuzul Imam Kauman')">
<meta property="og:description" content="{{ $metaDesc }}">
<meta property="og:url" content="{{ $metaUrl }}">
<meta property="og:image" content="{{ $metaImg }}">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="@yield('title', 'PP Kunuuzul Imam Kauman')">
<meta name="twitter:description" content="{{ $metaDesc }}">
<meta name="twitter:image" content="{{ $metaImg }}">
@if(config('services.google.verification'))
<meta name="google-site-verification" content="{{ config('services.google.verification') }}">
@endif
@php
  $orgSameAs = collect([
    \App\Models\Setting::get('sosmed_instagram_url'),
    \App\Models\Setting::get('sosmed_youtube_url'),
    \App\Models\Setting::get('sosmed_tiktok_url'),
    \App\Models\Setting::get('sosmed_facebook_url'),
    'https://makunuuzulimamkauman.sch.id/',
    'https://www.instagram.com/pp.kunuuzulimamkauman',
  ])->map(fn($u) => trim((string) $u))->filter(fn($u) => $u !== '' && $u !== '#' && str_starts_with($u, 'http'))->unique()->values()->all();
  // Kunci '@...' disusun via chr(64) agar tidak dibaca sebagai Blade directive.
  $at = chr(64);
  $ld = [
    $at . 'context' => 'https://schema.org',
    $at . 'graph' => [
      [
        $at . 'type' => 'Organization',
        $at . 'id' => url('/') . '#organisasi',
        'name' => 'PP Kunuuzul Imam Kauman',
        'alternateName' => ['Pondok Pesantren Kunuuzul Imam Kauman', 'Kunuuzul Imam Kauman', 'kunuuzulimamkauman'],
        'url' => url('/'),
        'logo' => asset('images/logo-pondok.png'),
        'sameAs' => $orgSameAs,
      ],
      [
        $at . 'type' => 'WebSite',
        $at . 'id' => url('/') . '#website',
        'url' => url('/'),
        'name' => 'PP Kunuuzul Imam Kauman',
        'alternateName' => 'kunuuzulimamkauman',
        'publisher' => [$at . 'id' => url('/') . '#organisasi'],
        'inLanguage' => 'id-ID',
      ],
    ],
  ];
  echo '<script type="application/ld+json">' . json_encode($ld, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>';
@endphp
<script>document.documentElement.classList.add('js');</script>
<style>html{background:#fafaf9;}</style>
<link rel="preload" as="image" href="/images/hero/slide-1.jpg" fetchpriority="high">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
@vite(['resources/css/app.css', 'resources/js/app.js'])
<script type="speculationrules">{"prerender":[{"source":"document","where":{"and":[{"href_matches":"/*"},{"not":{"href_matches":["/admin/*","/ppdb/*"]}}]},"eagerness":"moderate"}]}</script>
<script>(function(){const s=new Set();function pf(u){if(s.has(u)||!u.startsWith('/')&&!u.startsWith(location.origin))return;s.add(u);const l=document.createElement('link');l.rel='prefetch';l.href=u;document.head.appendChild(l);}document.addEventListener('mouseover',e=>{const a=e.target.closest&&e.target.closest('a[href]');if(!a)return;try{const u=new URL(a.href);if(u.origin===location.origin&&!u.pathname.startsWith('/admin'))pf(u.pathname);}catch(_){}},{passive:true});})();</script>
</head>
<body class="bg-stone-50 text-stone-800 min-h-screen flex flex-col">
@include('partials.intro')
<!-- Navbar ala Arus Logistik: bar gelap, logo + separator, menu uppercase, CTA kotak outline -->
<nav id="mainnav" data-turbo-permanent class="fixed top-0 inset-x-0 z-50 nav-solid">
  <input type="checkbox" id="mnavToggle" class="mnav-check" aria-hidden="true" tabindex="-1">
  <div id="mainnav-inner" class="max-w-7xl mx-auto px-4 h-[72px] flex justify-between items-center gap-6">
    <a href="{{ route('home') }}" class="flex items-center gap-3 shrink-0">
      <img src="{{ asset('images/logo-pondok.png') }}" alt="Logo PP Kunuuzul Imam Kauman" class="h-11 w-auto object-contain shrink-0">
      <span class="w-px h-9 bg-stone-300"></span>
      <div class="leading-tight">
        <p class="nav-brand-sub text-[10px] tracking-[0.25em] font-normal">PONDOK PESANTREN</p>
        <p class="nav-brand-title font-extrabold tracking-wide text-[16px] xl:text-[17px] whitespace-nowrap">KUNUUZUL IMAM KAUMAN</p>
      </div>
    </a>
    <div class="hidden lg:flex items-center gap-5 text-[11px] font-bold tracking-wider items-center text-stone-800">
      @php try{ $navBeritas = \App\Models\Berita::where('is_published',true)->latest('published_at')->take(4)->get(); }catch(\Throwable $e){ $navBeritas = collect(); } @endphp
      <a href="{{ route('home') }}" class="nav-link py-4">BERANDA</a>
      <div class="relative">
        <button onclick="toggleMainDrop('md-profil',event)" class="nav-link flex items-center gap-1 py-4">PROFIL <span class="text-[10px]">▾</span></button>
        <div id="md-profil" class="maindrop hidden absolute left-1/2 -translate-x-1/2 top-full pt-2 z-50 normal-case tracking-normal">
          <div class="bg-white rounded-none shadow-xl border w-52 py-2 text-stone-700">
            <a href="{{ route('profil.sejarah') }}" class="block px-4 py-2.5 hover:bg-stone-100 text-[13px] font-bold">Sejarah</a>
            <a href="{{ route('profil.struktur') }}" class="block px-4 py-2.5 hover:bg-stone-100 text-[13px] font-bold">Masyayikh</a>
            <a href="{{ route('profil.visi') }}" class="block px-4 py-2.5 hover:bg-stone-100 text-[13px] font-bold">Visi & Misi</a>
          </div>
        </div>
      </div>
      <a href="{{ route('berita.index') }}" class="nav-link py-4">BERITA</a>
      <a href="{{ route('artikel.index') }}" class="nav-link py-4">ARTIKEL</a>
      <div class="relative">
        <button onclick="toggleMainDrop('md-santri',event)" class="nav-link flex items-center gap-1 py-4">SANTRI <span class="text-[10px]">▾</span></button>
        <div id="md-santri" class="maindrop hidden absolute left-1/2 -translate-x-1/2 top-full pt-2 z-50 normal-case tracking-normal">
          <div class="bg-white rounded-none shadow-xl border w-52 py-2 text-stone-700">
            <a href="{{ route('santri.index') }}" class="block px-4 py-2.5 hover:bg-stone-100 text-[13px] font-bold">Kehidupan Santri</a>
            <a href="{{ route('galeri.index') }}" class="block px-4 py-2.5 hover:bg-stone-100 text-[13px] font-bold">Galeri</a>
            <a href="{{ route('berita.index', ['kategori' => 'prestasi']) }}" class="block px-4 py-2.5 hover:bg-stone-100 text-[13px] font-bold">Prestasi</a>
          </div>
        </div>
      </div>
      <div class="relative">
        <button onclick="toggleMainDrop('md-lain',event)" class="nav-link flex items-center gap-1 py-4">LAINNYA <span class="text-[10px]">▾</span></button>
        <div id="md-lain" class="maindrop hidden absolute right-0 top-full pt-2 z-50 normal-case tracking-normal">
          <div class="bg-white rounded-none shadow-xl border w-52 py-2 text-stone-700">
            <a href="{{ route('lembaga') }}" class="block px-4 py-2.5 hover:bg-stone-100 text-[13px] font-bold">Lembaga</a>
            <a href="{{ route('program.index') }}" class="block px-4 py-2.5 hover:bg-stone-100 text-[13px] font-bold">Program</a>
            <a href="{{ route('organisasi') }}" class="block px-4 py-2.5 hover:bg-stone-100 text-[13px] font-bold">Organisasi</a>
            <details class="group/layanan border-t border-stone-100 mt-1 pt-1">
              <summary class="flex items-center justify-between gap-2 px-4 py-2.5 hover:bg-stone-100 text-[13px] font-bold cursor-pointer list-none">
                <span>Layanan</span>
                <span class="layanan-chev text-[10px] transition-transform">▾</span>
              </summary>
              <div class="bg-stone-50/70 border-t border-stone-100 py-1">
                <a href="{{ route('psb.home') }}" class="block px-6 py-2 hover:bg-stone-100 text-[12.5px] font-bold">PSB</a>
                <a href="{{ route('surat.home') }}" class="block px-6 py-2 hover:bg-stone-100 text-[12.5px] font-bold">E-Surat</a>
                <a href="{{ route('alumni.home') }}" class="block px-6 py-2 hover:bg-stone-100 text-[12.5px] font-bold">Alumni</a>
              </div>
            </details>
          </div>
        </div>
      </div>
      <a href="{{ route('konsultasi') }}" class="nav-link py-4">KONSULTASI</a>
      <form action="{{ route('search') }}" method="GET" role="search" class="nav-search ml-1" title="Cari berita, program, atau halaman">
        <i class="fa-solid fa-magnifying-glass text-[12px]"></i>
        <input type="search" name="q" value="{{ request()->routeIs('search') ? request('q') : '' }}" maxlength="100"
               autocomplete="off" enterkeyhint="search" placeholder="Cari..." aria-label="Cari di situs">
        <button type="submit" aria-label="Cari"><i class="fa-solid fa-arrow-right text-[11px]"></i></button>
      </form>
    </div>
    <label for="mnavToggle" class="mnav-burger lg:hidden" aria-label="Buka / tutup menu"><span></span><span></span><span></span></label>
  </div>
  <label for="mnavToggle" id="mnavBackdrop" class="lg:hidden" aria-label="Tutup menu"></label>
  <aside id="mnav" class="lg:hidden" aria-label="Menu navigasi">
    <div class="mnav-panel">
      <div class="mnav-profile">
        <img src="{{ asset('images/logo-pondok.png') }}" alt="Logo PP Kunuuzul Imam Kauman">
        <div>
          <p>PONDOK PESANTREN</p>
          <h3>KUNUUZUL IMAM KAUMAN</h3>
        </div>
      </div>
      <div class="mnav-search">
        <form action="{{ route('search') }}" method="GET" role="search">
          <i class="fa-solid fa-magnifying-glass"></i>
          <input type="search" name="q" value="{{ request()->routeIs('search') ? request('q') : '' }}" maxlength="100"
                 autocomplete="off" enterkeyhint="search" placeholder="Cari berita, program, atau halaman..." aria-label="Cari di situs">
          <button type="submit" aria-label="Cari"><i class="fa-solid fa-arrow-right"></i></button>
        </form>
      </div>
      <ul class="mnav-list">
        <li class="{{ request()->routeIs('home') ? 'active' : '' }}"><a href="{{ route('home') }}">Beranda</a></li>
        @php $profilActive = request()->routeIs('profil.*'); @endphp
        <li class="mnav-group {{ $profilActive ? 'has-active' : '' }}">
          <details @if($profilActive) open @endif>
            <summary>Profil <span class="mnav-chev">▾</span></summary>
            <ul class="mnav-sublist">
              <li class="mnav-sub {{ request()->routeIs('profil.sejarah') ? 'active' : '' }}"><a href="{{ route('profil.sejarah') }}">Sejarah</a></li>
              <li class="mnav-sub {{ request()->routeIs('profil.struktur') ? 'active' : '' }}"><a href="{{ route('profil.struktur') }}">Masyayikh</a></li>
              <li class="mnav-sub {{ request()->routeIs('profil.visi') ? 'active' : '' }}"><a href="{{ route('profil.visi') }}">Visi &amp; Misi</a></li>
            </ul>
          </details>
        </li>
        <li class="{{ request()->routeIs('berita.index') && request('kategori') !== 'prestasi' ? 'active' : '' }}"><a href="{{ route('berita.index') }}">Berita</a></li>
        <li class="{{ request()->routeIs('artikel.index') ? 'active' : '' }}"><a href="{{ route('artikel.index') }}">Artikel</a></li>
        @php $santriActive = request()->routeIs('santri.*', 'galeri.*') || (request()->routeIs('berita.index') && request('kategori') === 'prestasi'); @endphp
        <li class="mnav-group {{ $santriActive ? 'has-active' : '' }}">
          <details @if($santriActive) open @endif>
            <summary>Santri <span class="mnav-chev">▾</span></summary>
            <ul class="mnav-sublist">
              <li class="mnav-sub {{ request()->routeIs('santri.*') ? 'active' : '' }}"><a href="{{ route('santri.index') }}">Kehidupan Santri</a></li>
              <li class="mnav-sub {{ request()->routeIs('galeri.*') ? 'active' : '' }}"><a href="{{ route('galeri.index') }}">Galeri</a></li>
              <li class="mnav-sub {{ request()->routeIs('berita.index') && request('kategori') === 'prestasi' ? 'active' : '' }}"><a href="{{ route('berita.index', ['kategori' => 'prestasi']) }}">Prestasi</a></li>
            </ul>
          </details>
        </li>
        @php $lainActive = request()->routeIs('lembaga') || request()->routeIs('program.*') || request()->routeIs('organisasi') || request()->routeIs('surat.*', 'psb.*', 'alumni.*'); @endphp
        <li class="mnav-group {{ $lainActive ? 'has-active' : '' }}">
          <details @if($lainActive) open @endif>
            <summary>Lainnya <span class="mnav-chev">▾</span></summary>
            <ul class="mnav-sublist">
              <li class="mnav-sub {{ request()->routeIs('lembaga') ? 'active' : '' }}"><a href="{{ route('lembaga') }}">Lembaga</a></li>
              <li class="mnav-sub {{ request()->routeIs('program.*') ? 'active' : '' }}"><a href="{{ route('program.index') }}">Program</a></li>
              <li class="mnav-sub {{ request()->routeIs('organisasi') ? 'active' : '' }}"><a href="{{ route('organisasi') }}">Organisasi</a></li>
              <li class="mnav-group mnav-subgroup">
                <details>
                  <summary>Layanan <span class="mnav-chev">▾</span></summary>
                  <ul class="mnav-sublist">
                    <li class="mnav-sub"><a href="{{ route('psb.home') }}">PSB</a></li>
                    <li class="mnav-sub"><a href="{{ route('surat.home') }}">E-Surat</a></li>
                    <li class="mnav-sub"><a href="{{ route('alumni.home') }}">Alumni</a></li>
                  </ul>
                </details>
              </li>
            </ul>
          </details>
        </li>
        <li class="{{ request()->routeIs('konsultasi') ? 'active' : '' }}"><a href="{{ route('konsultasi') }}">Konsultasi</a></li>
      </ul>
      <div class="mnav-foot">
        <a href="{{ route('psb.home') }}" class="block text-center bg-amber-400 text-primary-900 font-extrabold text-[13px] tracking-[0.15em] px-4 py-3 rounded-2xl hover:bg-amber-300 transition">DAFTAR PSB</a>
      </div>
    </div>
  </aside>
</nav>

{{-- Penyangga tinggi navbar fixed untuk halaman tanpa hero --}}
@sectionMissing('hero-full')
<div class="h-[72px]"></div>
@endif

@hasSection('hero-full')
@yield('hero-full')
@endif

<main class="flex-1">@yield('content')</main>

@include('partials.site-footer')
<button id="toTop" data-turbo-permanent onclick="window.scrollTo({top:0,behavior:'smooth'})" aria-label="Kembali ke atas" class="fixed bottom-5 right-5 z-50 w-11 h-11 rounded-full bg-primary-800 text-white shadow-xl items-center justify-center text-xl hover:bg-primary-700 hidden">↑</button>
</body>
</html>
