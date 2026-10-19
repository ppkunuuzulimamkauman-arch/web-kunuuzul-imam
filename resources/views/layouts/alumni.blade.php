<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@hasSection('title')@yield('title') | @endif Alumni PP Kunuuzul Imam Kauman</title>
<meta name="description" content="@yield('meta_description', 'Direktori dan silaturahmi alumni Pondok Pesantren Kunuuzul Imam Kauman.')">
<meta name="theme-color" content="#064e3b">
<link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=2">
<link rel="apple-touch-icon" href="{{ asset('images/logo-pondok.png') }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-stone-50 text-stone-800 min-h-screen flex flex-col">
<header class="bg-white/95 backdrop-blur border-b border-stone-200 sticky top-0 z-40">
  <div class="max-w-7xl mx-auto px-4 h-[72px] flex items-center justify-between gap-4">
    <a href="{{ route('alumni.home') }}" class="flex items-center gap-3 min-w-0">
      <img src="{{ asset('images/logo-pondok.png') }}" alt="Logo PP Kunuuzul Imam Kauman" class="h-11 w-auto object-contain shrink-0">
      <span class="w-px h-9 bg-stone-200 hidden sm:block"></span>
      <div class="leading-tight min-w-0">
        <p class="text-[10px] tracking-[0.25em] text-stone-500 font-semibold">IKATAN ALUMNI</p>
        <p class="font-extrabold text-[15px] md:text-[16px] text-stone-900 whitespace-nowrap">KUNUUZUL IMAM KAUMAN</p>
      </div>
    </a>
    <nav class="flex items-center gap-1 sm:gap-2 text-[13px] font-bold shrink-0">
      <a href="{{ route('alumni.home') }}" class="px-3 py-2 rounded-lg text-emerald-700 hover:bg-emerald-50 transition">Direktori</a>
      <a href="{{ config('app.url') }}" class="px-3 py-2 rounded-lg text-stone-600 hover:bg-stone-100 transition">Situs Utama</a>
    </nav>
  </div>
</header>

<main class="flex-1">
  @yield('content')
</main>

<footer class="bg-emerald-950 text-emerald-100/70 mt-16">
  <div class="max-w-7xl mx-auto px-4 py-10 flex flex-col md:flex-row items-center justify-between gap-4 text-[13px]">
    <p>&copy; {{ date('Y') }} PP Kunuuzul Imam Kauman</p>
    <a href="{{ config('app.url') }}" class="hover:text-white transition">Kembali ke situs utama</a>
  </div>
</footer>
</body>
</html>
