@extends('layouts.app')
@section('title', 'Profil Pondok')
@section('content')
<div class="bg-white">
<div class="max-w-5xl mx-auto px-4 py-8 md:py-10">
  <p class="text-[12px] font-bold text-stone-400 tracking-wider"><a href="{{ route('home') }}" class="hover:text-orange-600 transition">Beranda</a> <span class="mx-1">/</span> <span class="text-stone-600">Profil</span></p>
  <h1 class="font-extrabold text-stone-900 text-[26px] md:text-[30px] leading-tight mt-1.5">Profil Pondok Pesantren</h1>
  <div class="w-16 h-1 bg-orange-400 rounded-full mt-3 mb-6"></div>

  <div class="reveal border border-stone-200 rounded-2xl p-6 md:p-8 bg-white">
    <h2 class="font-extrabold text-xl text-stone-900">Sejarah Singkat</h2>
    <p class="text-[15px] mt-3 leading-relaxed text-stone-600 text-justify">{{ \App\Models\Setting::get('profil_sejarah_singkat', 'Pondok Pesantren Kunuuzul Imam Kauman didirikan sebagai wujud khidmah kepada umat di kawasan Kauman.') }}</p>
    <p class="text-[15px] mt-3 leading-relaxed text-stone-600 text-justify">Kurikulum memadukan salaf (sorogan, bandongan, hafalan) dan khalaf (sekolah formal, bahasa, teknologi) dengan pengawasan musyrif 24 jam.</p>
    <a href="{{ route('profil.sejarah') }}" class="inline-block mt-4 text-[13px] font-extrabold text-orange-600 hover:translate-x-1 transition-transform">Selengkapnya →</a>
  </div>

  <div class="grid md:grid-cols-2 gap-5 mt-5">
    <div class="reveal border border-stone-200 rounded-2xl p-6 bg-white hover:shadow-lg transition">
      <h2 class="font-extrabold text-lg text-stone-900">Visi & Misi</h2>
      <p class="text-[14px] mt-2 italic text-stone-600">"{{ \App\Models\Setting::get('profil_visi', 'Menjadi pondok pesantren unggul dalam tahfidz, ilmu syar\'i, dan akhlak karimah menuju generasi rabbani.') }}"</p>
      <a href="{{ route('profil.visi') }}" class="inline-block mt-4 text-[13px] font-extrabold text-orange-600 hover:translate-x-1 transition-transform">Selengkapnya →</a>
    </div>
    <div class="reveal border border-stone-200 rounded-2xl p-6 bg-white hover:shadow-lg transition">
      <h2 class="font-extrabold text-lg text-stone-900">Masyayikh</h2>
      @php
        $ringkas = (isset($pengasuhs) && $pengasuhs->count()) ? $pengasuhs : collect([
          (object)['nama' => 'KH. Imam Kauman', 'jabatan' => 'Pengasuh'],
          (object)['nama' => 'Ust. Ahmad Fauzi, Lc.', 'jabatan' => "Mudir Ma'had"],
          (object)['nama' => 'Ustzh. Maryam Hafizhah', 'jabatan' => 'Musyrifah Putri'],
        ]);
      @endphp
      <ul class="text-[14px] mt-3 space-y-2.5 text-stone-600">
        @foreach($ringkas as $u)
        <li><b class="text-stone-800">{{ $u->nama }}</b> — {{ explode('—', $u->jabatan)[0] }}</li>
        @endforeach
      </ul>
      <a href="{{ route('profil.struktur') }}" class="inline-block mt-4 text-[13px] font-extrabold text-orange-600 hover:translate-x-1 transition-transform">Selengkapnya →</a>
    </div>
  </div>

  <div class="reveal rounded-2xl p-6 md:p-8 mt-5 bg-emerald-950 text-white relative overflow-hidden">
    <h2 class="font-extrabold text-lg">Fasilitas</h2>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mt-4 text-[13.5px] font-bold">
      @foreach(explode("\n", \App\Models\Setting::get('profil_fasilitas', "Masjid & Aula\nAsrama Putra-Putri\nPerpustakaan\nLab Komputer\nLapangan & Panahan\nDapur & Kantin\nKlinik Santri\nKoperasi")) as $f)
      @if(trim($f) !== '')<div class="bg-white/10 border border-white/10 rounded-xl p-3 text-center hover:bg-white/20 transition">{{ trim($f) }}</div>@endif
      @endforeach
    </div>
    <div class="absolute -right-10 -bottom-10 w-40 h-40 rounded-full bg-amber-400/10 pointer-events-none"></div>
  </div>
</div>
</div>
@endsection
