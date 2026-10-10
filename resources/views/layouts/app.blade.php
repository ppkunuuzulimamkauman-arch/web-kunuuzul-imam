<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'PP Kunuuzul Imam Kauman')</title>
@php
  $metaDesc = trim($__env->yieldContent('meta_description')) ?: "Pondok Pesantren Kunuuzul Imam Kauman — Tahfidzul Qur'an 30 juz, kajian kitab kuning, sekolah formal MTs-MA, dan tarbiyah akhlak 24 jam.";
  $metaImg = trim($__env->yieldContent('meta_image')) ?: asset('images/tirai.jpg');
  $metaUrl = url()->current();
@endphp
<meta name="description" content="{{ $metaDesc }}">
<link rel="canonical" href="{{ $metaUrl }}">
<meta name="theme-color" content="#064e3b">
<link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
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
          </div>
        </div>
      </div>
      <a href="{{ route('konsultasi') }}" class="nav-link py-4">KONSULTASI</a>
      <div class="flex items-center gap-3 ml-3">
        <a href="{{ route('ppdb.form') }}" class="nav-cta px-4 py-2 text-[11px] font-bold tracking-[0.15em] transition">DAFTAR PSB</a>
      </div>
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
        @php $santriActive = request()->routeIs('galeri.*') || (request()->routeIs('berita.index') && request('kategori') === 'prestasi'); @endphp
        <li class="mnav-group {{ $santriActive ? 'has-active' : '' }}">
          <details @if($santriActive) open @endif>
            <summary>Santri <span class="mnav-chev">▾</span></summary>
            <ul class="mnav-sublist">
              <li class="mnav-sub {{ request()->routeIs('galeri.*') ? 'active' : '' }}"><a href="{{ route('galeri.index') }}">Galeri</a></li>
              <li class="mnav-sub {{ request()->routeIs('berita.index') && request('kategori') === 'prestasi' ? 'active' : '' }}"><a href="{{ route('berita.index', ['kategori' => 'prestasi']) }}">Prestasi</a></li>
            </ul>
          </details>
        </li>
        @php $lainActive = request()->routeIs('lembaga') || request()->routeIs('program.*') || request()->routeIs('organisasi'); @endphp
        <li class="mnav-group {{ $lainActive ? 'has-active' : '' }}">
          <details @if($lainActive) open @endif>
            <summary>Lainnya <span class="mnav-chev">▾</span></summary>
            <ul class="mnav-sublist">
              <li class="mnav-sub {{ request()->routeIs('lembaga') ? 'active' : '' }}"><a href="{{ route('lembaga') }}">Lembaga</a></li>
              <li class="mnav-sub {{ request()->routeIs('program.*') ? 'active' : '' }}"><a href="{{ route('program.index') }}">Program</a></li>
              <li class="mnav-sub {{ request()->routeIs('organisasi') ? 'active' : '' }}"><a href="{{ route('organisasi') }}">Organisasi</a></li>
            </ul>
          </details>
        </li>
        <li class="{{ request()->routeIs('konsultasi') ? 'active' : '' }}"><a href="{{ route('konsultasi') }}">Konsultasi</a></li>
      </ul>
      <div class="mnav-foot">
        <a href="{{ route('ppdb.form') }}" class="block text-center bg-amber-400 text-primary-900 font-extrabold text-[13px] tracking-[0.15em] px-4 py-3 rounded-2xl hover:bg-amber-300 transition">DAFTAR PSB</a>
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

<footer class="bg-primary-900 text-primary-100 mt-16">
  <div class="h-1 bg-amber-400/90"></div>
  <div class="max-w-7xl mx-auto px-4 py-12 grid gap-10 md:grid-cols-4">
    <div>
      <div class="flex items-center gap-2.5 mb-4">
        <img src="{{ asset('images/logo-pondok.png') }}" alt="Logo PP Kunuuzul Imam Kauman" class="h-12 w-auto object-contain shrink-0">
        <div>
          <p class="text-[11px] font-semibold tracking-[0.2em] text-primary-100/70 leading-tight">PONDOK PESANTREN</p>
          <p class="font-bold text-white text-[16px] leading-tight">Kunuuzul Imam Kauman</p>
        </div>
      </div>
      <p class="text-sm leading-relaxed text-primary-100/80">{{ \App\Models\Setting::get('footer_deskripsi', "Pondok Pesantren Ahlussunnah wal Jama'ah. Fokus Tahfidzul Qur'an, Kajian Kitab Kuning, dan Tarbiyah Akhlak.") }}</p>
    </div>
    <div>
      <p class="font-bold text-white mb-1">{{ \App\Models\Setting::get('footer_lembaga_judul', 'Lembaga') }}</p>
      <div class="w-8 h-0.5 bg-amber-400 rounded-full mb-4"></div>
      <ul class="text-sm space-y-2.5 text-primary-100/80">
        @php
          $lembagaRaw = \App\Models\Setting::get('footer_lembaga_isi', "MADRASAH DINIYAH TASHWIRUL AFKAR|/lembaga\nMA Kunuuzul Imam|https://makunuuzulimamkauman.sch.id/\nSMP Kunuuzul Imam|/lembaga");
          $lembagaLinks = collect(preg_split('/\r\n|\r|\n/', (string) $lembagaRaw))->map(fn($l) => trim($l))->filter()->map(function($l){
            $p = explode('|', $l, 2);
            return ['label' => trim($p[0]), 'url' => trim($p[1] ?? '')];
          });
        @endphp
        @forelse($lembagaLinks as $link)
          <li>
            @if($link['url'] !== '')
              <a href="{{ $link['url'] }}" @if(str_starts_with($link['url'],'http')) target="_blank" rel="noopener" @endif class="hover:text-amber-300 transition">{{ $link['label'] }}</a>
            @else
              {{ $link['label'] }}
            @endif
          </li>
        @empty
          <li><a href="{{ route('lembaga') }}" class="hover:text-amber-300 transition">Lembaga</a></li>
        @endforelse
      </ul>
    </div>
    <div>
      <p class="font-bold text-white mb-1">{{ \App\Models\Setting::get('footer_program_judul', 'Program Unggulan') }}</p>
      <div class="w-8 h-0.5 bg-amber-400 rounded-full mb-4"></div>
      <ul class="text-sm space-y-2.5 text-primary-100/80">
        @php
          $programRaw = \App\Models\Setting::get('footer_program_isi', "Tahfidzul Qur'an 30 Juz\nMadrasah Diniyah & Kitab Kuning\nMadrasah Formal (MTs-MA)\nBahasa Arab & Inggris");
          $programItems = collect(preg_split('/\r\n|\r|\n/', (string) $programRaw))->map(fn($l) => trim($l))->filter();
        @endphp
        @forelse($programItems as $item)
          <li>{{ $item }}</li>
        @empty
          <li>Tahfidzul Qur'an 30 Juz</li>
        @endforelse
      </ul>
    </div>
    <div>
      <p class="font-bold text-white mb-1">{{ \App\Models\Setting::get('footer_kontak_judul', 'Kontak') }}</p>
      <div class="w-8 h-0.5 bg-amber-400 rounded-full mb-4"></div>
      <ul class="text-sm space-y-2.5 text-primary-100/80">
        <li>📍 {{ \App\Models\Setting::get('kontak_alamat', 'Kauman, Jawa Tengah, Indonesia') }}</li>
        <li>📞 {{ \App\Models\Setting::get('kontak_telp', '0812-3456-7890') }} (Ust. Ahmad)</li>
        <li>✉️ {{ \App\Models\Setting::get('kontak_email', 'info@kunuuzulimam.or.id') }}</li>
        <li>🕐 {{ \App\Models\Setting::get('kontak_jam', 'Senin–Sabtu 08.00–15.00') }}</li>
      </ul>
    </div>
  </div>
  <div class="border-t border-white/10">
    <div class="max-w-7xl mx-auto px-4 py-4 text-xs text-primary-100/70">
      <p>Copyright © {{ date('Y') }} {{ \App\Models\Setting::get('footer_copyright', 'Pondok Pesantren Kunuuzul Imam Kauman') }}</p>
    </div>
  </div>
</footer>
<button id="toTop" data-turbo-permanent onclick="window.scrollTo({top:0,behavior:'smooth'})" aria-label="Kembali ke atas" class="fixed bottom-5 right-5 z-50 w-11 h-11 rounded-full bg-primary-800 text-white shadow-xl items-center justify-center text-xl hover:bg-primary-700 hidden">↑</button>
</body>
</html>
