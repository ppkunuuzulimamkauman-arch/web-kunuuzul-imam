@extends('layouts.app')
@section('title', 'Kontak Pondok')
@section('content')
<div class="bg-white">
<div class="max-w-5xl mx-auto px-4 py-8 md:py-10">
  <p class="text-[12px] font-bold text-stone-400 tracking-wider"><a href="{{ route('home') }}" class="hover:text-orange-600 transition">Beranda</a> <span class="mx-1">/</span> <span class="text-stone-600">Kontak</span></p>
  <h1 class="font-extrabold text-stone-900 text-[26px] md:text-[30px] leading-tight mt-1.5">Hubungi Kami</h1>
  <div class="w-16 h-1 bg-orange-400 rounded-full mt-3 mb-6"></div>

  <div class="grid md:grid-cols-2 gap-6 items-start">
    <div class="reveal border border-stone-200 rounded-2xl p-6 text-[14px] space-y-3 bg-white">
      @php $S = \App\Models\Setting::class; @endphp
      <p class="flex gap-3"><span class="text-lg">📍</span><span><b>Alamat:</b> {{ $S::get('kontak_alamat', 'Jl. Kauman No. 1, Jawa Tengah') }}</span></p>
      <p class="flex gap-3"><span class="text-lg">📞</span><span><b>Telp/WA:</b> {{ $S::get('kontak_telp', '0812-3456-7890') }}</span></p>
      <p class="flex gap-3"><span class="text-lg">✉️</span><span><b>Email:</b> {{ $S::get('kontak_email', 'info@kunuuzulimam.or.id') }}</span></p>
      <p class="flex gap-3"><span class="text-lg">🕐</span><span><b>Jam:</b> {{ $S::get('kontak_jam', 'Senin–Sabtu 08.00–15.00') }}</span></p>
      <div class="rounded-xl overflow-hidden border border-stone-200"><iframe src="https://maps.google.com/maps?q=Kauman&t=&z=15&ie=UTF8&iwloc=&output=embed" class="w-full h-56" loading="lazy"></iframe></div>
    </div>
    <div class="reveal border border-stone-200 rounded-2xl p-6 bg-white">
      @if(session('sukses'))<div class="bg-green-50 border border-green-200 text-green-800 rounded-xl p-3 text-sm mb-3">{{ session('sukses') }}</div>@endif
      <form action="{{ route('kontak.store') }}" method="POST" class="space-y-3">
        @csrf
        <input name="nama" required placeholder="Nama Anda" class="w-full border border-stone-200 rounded-xl px-4 py-2.5 text-[14px] focus:outline-none focus:border-emerald-700 focus:ring-1 focus:ring-emerald-700 transition">
        <div class="grid grid-cols-2 gap-3">
          <input name="email" type="email" placeholder="Email" class="w-full border border-stone-200 rounded-xl px-4 py-2.5 text-[14px] focus:outline-none focus:border-emerald-700 focus:ring-1 focus:ring-emerald-700 transition">
          <input name="no_hp" placeholder="No HP/WA" class="w-full border border-stone-200 rounded-xl px-4 py-2.5 text-[14px] focus:outline-none focus:border-emerald-700 focus:ring-1 focus:ring-emerald-700 transition">
        </div>
        <input name="subjek" placeholder="Subjek (mis: Tanya PPDB)" class="w-full border border-stone-200 rounded-xl px-4 py-2.5 text-[14px] focus:outline-none focus:border-emerald-700 focus:ring-1 focus:ring-emerald-700 transition">
        <textarea name="pesan" required rows="4" placeholder="Tulis pesan..." class="w-full border border-stone-200 rounded-xl px-4 py-2.5 text-[14px] focus:outline-none focus:border-emerald-700 focus:ring-1 focus:ring-emerald-700 transition"></textarea>
        @include('partials.turnstile')
        <button class="w-full bg-emerald-950 hover:bg-emerald-800 text-white font-extrabold text-[13px] tracking-widest py-3.5 rounded-xl transition">KIRIM PESAN →</button>
      </form>
    </div>
  </div>
</div>
</div>
@endsection
