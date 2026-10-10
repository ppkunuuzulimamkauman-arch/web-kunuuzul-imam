@extends('layouts.app')
@section('title', $berita->judul)
@section('meta_description', \Str::limit(trim(preg_replace('/\s+/', ' ', strip_tags($berita->excerpt ?? $berita->konten ?? ''))), 160))
@section('meta_image', $berita->gambar ? asset('storage/'.$berita->gambar) : '')
@section('content')
<div class="bg-white">
<div class="max-w-3xl mx-auto px-4 py-8 md:py-10">
  <a href="{{ route('berita.index') }}" class="text-[13px] font-extrabold text-orange-600 hover:-translate-x-1 transition-transform inline-block">← Semua Berita</a>
  <p class="flex items-center gap-3 text-[13px] text-stone-500 mt-4">{{ $berita->published_at ? $berita->published_at->translatedFormat('d F Y') : '' }} <span class="bg-amber-500 text-white text-[12px] font-bold px-3 py-1 rounded-lg">{{ ucfirst($berita->kategori ?? 'Berita') }}</span></p>
  <h1 class="font-extrabold text-stone-900 text-[24px] md:text-[30px] leading-tight mt-3">{{ $berita->judul }}</h1>
  <p class="text-[13px] text-stone-500 mt-2">Oleh <b class="text-stone-700">{{ $berita->penulis ?? 'Redaksi Kauman' }}</b> • 👁 {{ number_format($berita->views ?? 0) }} dibaca</p>

  @php $bimg = $berita->gambar ? asset('storage/'.$berita->gambar) : null; @endphp
  @if($bimg)
  <div class="reveal rounded-2xl overflow-hidden mt-5 border border-stone-100"><img src="{{ $bimg }}" alt="{{ $berita->judul }}" fetchpriority="high" decoding="async" class="w-full max-h-[420px] object-cover"></div>
  @else
  <div class="reveal h-56 bg-amber-50 rounded-2xl mt-5 flex items-center justify-center text-6xl border border-stone-100">📰</div>
  @endif

  <div class="reveal border border-stone-200 rounded-2xl p-6 mt-5 text-[15px] leading-relaxed text-stone-700">{!! nl2br(e($berita->konten)) !!}</div>

  {{-- Bagikan --}}
  @php $shareUrl = url()->current(); $shareText = $berita->judul; @endphp
  <div class="reveal flex flex-wrap items-center gap-2 mt-5">
    <span class="text-[12px] font-extrabold tracking-widest text-stone-400">BAGIKAN:</span>
    <a href="https://api.whatsapp.com/send?text={{ urlencode($shareText.' '.$shareUrl) }}" target="_blank" rel="noopener" aria-label="Bagikan ke WhatsApp" class="text-[12px] font-bold border border-stone-200 rounded-full px-4 py-1.5 hover:bg-emerald-950 hover:text-white hover:border-emerald-950 transition flex items-center gap-1.5"><i class="fa-brands fa-whatsapp text-[14px]"></i> WA</a>
    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($shareUrl) }}" target="_blank" rel="noopener" aria-label="Bagikan ke Facebook" class="text-[12px] font-bold border border-stone-200 rounded-full px-4 py-1.5 hover:bg-emerald-950 hover:text-white hover:border-emerald-950 transition flex items-center gap-1.5"><i class="fa-brands fa-facebook-f text-[14px]"></i> FB</a>
    <a href="https://twitter.com/intent/tweet?text={{ urlencode($shareText) }}&url={{ urlencode($shareUrl) }}" target="_blank" rel="noopener" aria-label="Bagikan ke X" class="text-[12px] font-bold border border-stone-200 rounded-full px-4 py-1.5 hover:bg-emerald-950 hover:text-white hover:border-emerald-950 transition flex items-center gap-1.5"><i class="fa-brands fa-x-twitter text-[14px]"></i> X</a>
    <button type="button" onclick="shareToIG(this)" aria-label="Bagikan ke Instagram" class="text-[12px] font-bold border border-stone-200 rounded-full px-4 py-1.5 hover:bg-emerald-950 hover:text-white hover:border-emerald-950 transition flex items-center gap-1.5"><i class="fa-brands fa-instagram text-[14px]"></i> <span>IG</span></button>
    <button type="button" onclick="copyShareLink(this)" aria-label="Salin link berita" class="text-[12px] font-bold border border-stone-200 rounded-full px-4 py-1.5 hover:bg-emerald-950 hover:text-white hover:border-emerald-950 transition flex items-center gap-1.5"><i class="fa-solid fa-link text-[13px]"></i> <span>Salin Link</span></button>
  </div>
  <script>
  function copyTextFallback(text, done) {
    try {
      const ta = document.createElement('textarea');
      ta.value = text;
      ta.style.position = 'fixed';
      ta.style.opacity = '0';
      document.body.appendChild(ta);
      ta.select();
      document.execCommand('copy');
      document.body.removeChild(ta);
      done(true);
    } catch (_) { done(false); }
  }
  function copyShareLink(btn) {
    const url = window.location.href;
    const label = btn.querySelector('span') || btn;
    const ok = () => { const t = label.textContent; label.textContent = 'Tersalin ✓'; setTimeout(() => label.textContent = t === 'Tersalin ✓' ? 'Salin Link' : t, 1500); };
    if (navigator.clipboard && navigator.clipboard.writeText) {
      navigator.clipboard.writeText(url).then(ok).catch(() => copyTextFallback(url, (s) => s && ok()));
    } else copyTextFallback(url, (s) => s && ok());
  }
  function shareToIG(btn) {
    // Instagram tidak punya URL share web — salin link lalu buka Instagram
    const url = window.location.href;
    const label = btn.querySelector('span') || btn;
    const go = () => {
      const t = label.textContent;
      label.textContent = 'Link disalin!';
      setTimeout(() => label.textContent = 'IG', 1800);
      setTimeout(() => window.open('https://www.instagram.com/', '_blank', 'noopener'), 600);
    };
    if (navigator.clipboard && navigator.clipboard.writeText) {
      navigator.clipboard.writeText(url).then(go).catch(() => copyTextFallback(url, (s) => s && go()));
    } else copyTextFallback(url, (s) => s && go());
  }
  </script>

  @if($terkait->count())
  <h3 class="font-extrabold text-stone-900 text-[18px] mt-10">Berita Terkait</h3>
  <div class="w-12 h-1 bg-orange-400 rounded-full mt-2 mb-4"></div>
  <div class="grid md:grid-cols-3 gap-4">
    @foreach($terkait as $t)
    <a href="{{ route('berita.show', $t->slug) }}" class="reveal border border-stone-200 rounded-2xl p-5 text-[14px] font-bold text-stone-800 hover:shadow-lg hover:-translate-y-1 hover:text-orange-600 transition">{{ $t->judul }}</a>
    @endforeach
  </div>
  @endif
</div>
</div>
@endsection
