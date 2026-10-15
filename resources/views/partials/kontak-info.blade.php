@php
  $S = \App\Models\Setting::class;
  $asramas = [
    ['label' => 'Asrama 1', 'alamat' => $S::get('kontak_alamat_1', ''), 'wa' => $S::get('kontak_wa_1', '')],
  ];
  $waLink = function ($no) {
    $d = preg_replace('/[^0-9]/', '', (string) $no);
    if (str_starts_with($d, '0')) $d = '62' . substr($d, 1);
    return strlen($d) >= 10 && str_contains((string) $no, 'x') === false ? 'https://wa.me/' . $d : null;
  };
@endphp
<div class="reveal border border-stone-200 rounded-2xl p-6 text-[14px] space-y-5 bg-white">
  @foreach($asramas as $a)
  <div>
    <p class="font-extrabold text-emerald-950 text-[15px]">📍 {{ $a['label'] }}</p>
    <p class="text-stone-600 mt-1">{{ $a['alamat'] }}</p>
    @if($a['wa'] !== '' && $waLink($a['wa']))
      <a href="{{ $waLink($a['wa']) }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 mt-2 text-[12.5px] font-bold text-white bg-emerald-700 hover:bg-emerald-600 rounded-full px-4 py-1.5 transition"><i class="fa-brands fa-whatsapp"></i> {{ $a['wa'] }}</a>
    @elseif($a['wa'] !== '')
      <p class="text-stone-600 mt-1">📞 {{ $a['wa'] }}</p>
    @endif
    @if(trim((string) $a['alamat']) !== '' && ! str_contains($a['alamat'], 'ganti di Pengaturan'))
      <div class="rounded-xl overflow-hidden border border-stone-200 mt-3"><iframe src="https://maps.google.com/maps?q={{ urlencode($a['alamat']) }}&t=&z=15&ie=UTF8&iwloc=&output=embed" class="w-full h-44" loading="lazy" title="Peta {{ $a['label'] }}"></iframe></div>
    @endif
  </div>
  @endforeach
  <p class="flex gap-3"><span class="text-lg">🕐</span><span><b>Jam:</b> {{ $S::get('kontak_jam', 'Senin–Sabtu 08.00–15.00') }}</span></p>
</div>
