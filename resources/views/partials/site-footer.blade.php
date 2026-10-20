@php
  $mainUrl = rtrim((string) config('app.url'), '/');
@endphp

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
          $lembagaLinks = collect(preg_split('/\r\n|\r|\n/', (string) $lembagaRaw))->map(fn($l) => trim($l))->filter()->map(function($l) use ($mainUrl){
            $p = explode('|', $l, 2);
            $url = trim($p[1] ?? '');
            if ($url !== '' && ! preg_match('#^([a-z][a-z0-9+.-]*:|//)#i', $url)) {
              $url = $mainUrl . '/' . ltrim($url, '/');
            }
            return ['label' => trim($p[0]), 'url' => $url];
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
          <li><a href="{{ $mainUrl }}/lembaga" class="hover:text-amber-300 transition">Lembaga</a></li>
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
        @php
          $wa1 = trim((string) \App\Models\Setting::get('kontak_wa_1', ''));
          $waUrl = function ($no) {
            $d = preg_replace('/[^0-9]/', '', (string) $no);
            if (str_starts_with($d, '0')) $d = '62' . substr($d, 1);
            return strlen($d) >= 10 && ! str_contains((string) $no, 'x') ? 'https://wa.me/' . $d : null;
          };
        @endphp
        <li>
          <p>{{ \App\Models\Setting::get('kontak_alamat_1', '') }}</p>
          @if($wa1 !== '')<p class="mt-1">📞 @if($waUrl($wa1))<a href="{{ $waUrl($wa1) }}" target="_blank" rel="noopener" class="hover:text-amber-300 transition">{{ $wa1 }}</a>@else{{ $wa1 }}@endif</p>@endif
        </li>
        <li>🕐 {{ \App\Models\Setting::get('kontak_jam', 'Senin–Sabtu 08.00–15.00') }}</li>
      </ul>
    </div>
  </div>
  <div class="border-t border-white/10">
    <div class="max-w-7xl mx-auto px-4 py-4 text-xs text-primary-100/70 flex flex-wrap gap-x-4 gap-y-1 items-center justify-between">
      <p>Copyright © {{ date('Y') }} {{ \App\Models\Setting::get('footer_copyright', 'Pondok Pesantren Kunuuzul Imam Kauman') }}</p>
      <p><a href="{{ $mainUrl }}/privasi" class="hover:text-amber-300 transition">Kebijakan Privasi</a></p>
    </div>
  </div>
</footer>
