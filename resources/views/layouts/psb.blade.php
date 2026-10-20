<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
@php
  $mainUrl = rtrim((string) config('app.url'), '/');
  $pageTitle = trim($__env->yieldContent('title'));
@endphp
<title>{{ $pageTitle !== '' ? $pageTitle.' | ' : '' }}PSB PP Kunuuzul Imam Kauman</title>
<meta name="description" content="@yield('meta_description', 'Informasi Penerimaan Santri Baru (PSB) Pondok Pesantren Kunuuzul Imam Kauman — syarat, alur pendaftaran, dan pendaftaran online.')">
<link rel="canonical" href="{{ url()->current() }}">
<meta name="theme-color" content="#064e3b">
<link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=2">
<link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=2">
<link rel="apple-touch-icon" href="{{ asset('images/logo-pondok.png') }}">
<meta property="og:site_name" content="PP Kunuuzul Imam Kauman">
<meta property="og:locale" content="id_ID">
<meta property="og:type" content="website">
<meta property="og:title" content="@yield('title', 'Penerimaan Santri Baru')">
<meta property="og:description" content="@yield('meta_description', 'Informasi Penerimaan Santri Baru (PSB) Pondok Pesantren Kunuuzul Imam Kauman.')">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:image" content="@yield('meta_image', asset('images/hero/slide-2.jpg'))">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="@yield('title', 'Penerimaan Santri Baru')">
<meta name="twitter:description" content="@yield('meta_description', 'Informasi Penerimaan Santri Baru (PSB) Pondok Pesantren Kunuuzul Imam Kauman.')">
<meta name="twitter:image" content="@yield('meta_image', asset('images/hero/slide-2.jpg'))">
<script>document.documentElement.classList.add('js');</script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
@vite(['resources/css/app.css', 'resources/js/app.js'])
@stack('head')
</head>
<body class="bg-stone-50 text-stone-800 min-h-screen flex flex-col">
<header class="bg-white/95 backdrop-blur border-b border-stone-200 sticky top-0 z-40">
  <div class="max-w-7xl mx-auto px-4 h-[72px] flex items-center justify-between gap-4">
    <a href="{{ route('psb.home') }}" class="flex items-center gap-2 sm:gap-3 min-w-0">
      <img src="{{ asset('images/logo-pondok.png') }}" alt="Logo PP Kunuuzul Imam Kauman" class="h-9 sm:h-11 w-auto object-contain shrink-0">
      <span class="w-px h-9 bg-stone-200 hidden sm:block"></span>
      <div class="leading-tight min-w-0 hidden sm:block">
        <p class="text-[10px] tracking-[0.25em] text-stone-500 font-semibold">PENERIMAAN SANTRI BARU</p>
        <p class="font-extrabold text-[15px] md:text-[16px] text-stone-900 whitespace-nowrap">KUNUUZUL IMAM KAUMAN</p>
      </div>
      <div class="leading-tight sm:hidden">
        <p class="text-[9px] tracking-[0.22em] text-stone-500 font-bold">PSB</p>
        <p class="font-extrabold text-[11px] text-stone-900 whitespace-nowrap">KUNUUZUL</p>
      </div>
    </a>
    <nav class="flex items-center gap-1 sm:gap-2 text-[12px] sm:text-[13px] font-bold shrink-0" aria-label="Navigasi PSB">
      <a href="#alur" class="hidden md:inline-block px-3 py-2 rounded-lg text-stone-600 hover:bg-stone-100 transition">Alur</a>
      <a href="#syarat" class="hidden md:inline-block px-3 py-2 rounded-lg text-stone-600 hover:bg-stone-100 transition">Syarat</a>
      <a href="{{ $mainUrl }}/ppdb/cek" class="px-2 sm:px-3 py-2 rounded-lg text-emerald-800 hover:bg-emerald-50 transition">Cek Status</a>
      <a href="{{ $mainUrl }}/ppdb" class="px-2.5 sm:px-3 py-2 rounded-lg bg-emerald-800 text-white hover:bg-emerald-900 transition">Daftar</a>
      <a href="{{ $mainUrl }}" class="hidden xl:inline-block px-3 py-2 rounded-lg text-stone-500 hover:bg-stone-100 transition">Situs Utama</a>
    </nav>
  </div>
</header>

<main class="flex-1">
  @yield('content')
</main>

@include('partials.site-footer')
@stack('scripts')
</body>
</html>
