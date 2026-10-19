<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@hasSection('title')@yield('title') | @endif Alumni PP Kunuuzul Imam Kauman</title>
<meta name="description" content="@yield('meta_description', 'Pendaftaran dan sensus alumni Pondok Pesantren Kunuuzul Imam Kauman.')">
<meta name="theme-color" content="#0b0f14">
@hasSection('robots')
<meta name="robots" content="@yield('robots')">
@endif
<link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=2">
<link rel="apple-touch-icon" href="{{ asset('images/logo-pondok.png') }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
@vite(['resources/css/app.css', 'resources/js/app.js'])
<style>
  dialog::backdrop { background: rgba(0, 0, 0, .72); }
</style>
</head>
<body class="bg-[#0b0f14] text-slate-200 min-h-screen flex flex-col antialiased">
@php $totalAlumni = $totalAlumni ?? \App\Models\Alumni::tampil()->count(); @endphp

<div class="fixed inset-0 -z-10 pointer-events-none bg-[radial-gradient(ellipse_at_top,_rgba(16,185,129,0.10),_transparent_55%)]"></div>

<header class="relative z-30 border-b border-white/10 bg-[#0b0f14]/80 backdrop-blur">
  <div class="max-w-5xl mx-auto px-4 h-16 flex items-center justify-between gap-4">
    <a href="{{ route('alumni.register') }}" class="flex items-center gap-3 min-w-0">
      <span class="h-9 w-9 rounded-full bg-white/10 border border-white/10 flex items-center justify-center overflow-hidden shrink-0">
        <img src="{{ asset('images/logo-pondok.png') }}" alt="Logo PP Kunuuzul Imam Kauman" class="h-7 w-7 object-contain">
      </span>
      <div class="leading-tight min-w-0">
        <p class="font-extrabold text-sm text-white truncate">Pendaftaran Alumni</p>
        <p class="text-[10px] text-slate-400 truncate">Sensus Alumni PP Kunuuzul Imam Kauman</p>
      </div>
    </a>
    <div class="flex items-center gap-3 sm:gap-5 shrink-0">
      <div class="text-right hidden sm:block">
        <p class="text-base font-extrabold text-emerald-400 leading-none">{{ $totalAlumni }}</p>
        <p class="text-[10px] text-slate-500">Total alumni</p>
      </div>
      <a href="{{ route('alumni.home') }}" class="text-[12px] font-bold text-slate-300 hover:text-white transition">Direktori</a>
    </div>
  </div>
</header>

<main class="flex-1 relative z-10">
  @yield('content')
</main>

<footer class="relative z-10 border-t border-white/10 mt-auto">
  <div class="max-w-5xl mx-auto px-4 py-6 flex flex-col sm:flex-row items-center justify-between gap-3 text-[11px] text-slate-500">
    <p>&copy; {{ date('Y') }} PP Kunuuzul Imam Kauman</p>
    <div class="flex items-center gap-4">
      <a href="{{ route('alumni.home') }}" class="hover:text-slate-300 transition">Direktori</a>
      <a href="{{ route('alumni.stats') }}" class="hover:text-slate-300 transition">Statistik</a>
      <a href="{{ config('app.url') }}" class="hover:text-slate-300 transition">Situs Utama</a>
    </div>
  </div>
</footer>

@stack('scripts')
</body>
</html>
