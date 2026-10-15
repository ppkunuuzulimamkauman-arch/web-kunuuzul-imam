@extends('layouts.app')
@section('title', 'Konsultasi Pondok')
@section('content')
<div class="bg-white">
<div class="max-w-5xl mx-auto px-4 py-8 md:py-10">
  <p class="text-[12px] font-bold text-stone-400 tracking-wider"><a href="{{ route('home') }}" class="hover:text-orange-600 transition">Beranda</a> <span class="mx-1">/</span> <span class="text-stone-600">Konsultasi</span></p>
  <h1 class="font-extrabold text-stone-900 text-[26px] md:text-[30px] leading-tight mt-1.5">Konsultasi</h1>
  <div class="w-16 h-1 bg-orange-400 rounded-full mt-3 mb-6"></div>
  <p class="text-[14px] text-stone-500 leading-relaxed max-w-2xl">Ada yang ingin ditanyakan seputar pendidikan, tahfidz, PPDB, atau keluarga? Pilih kategori, tulis pertanyaan Anda — insyaAllah kami balas via WA.</p>

  <div class="grid md:grid-cols-2 gap-6 items-start mt-6">
    @include('partials.kontak-info')
    <div class="reveal border border-stone-200 rounded-2xl p-6 bg-white">
      @if(session('sukses'))<div class="bg-green-50 border border-green-200 text-green-800 rounded-xl p-3 text-sm mb-3">{{ session('sukses') }}</div>@endif
      <form action="{{ route('konsultasi.store') }}" method="POST" class="space-y-3">
        @csrf
        <input name="nama" required placeholder="Nama Anda" value="{{ old('nama') }}" class="w-full border border-stone-200 rounded-xl px-4 py-2.5 text-[14px] focus:outline-none focus:border-emerald-700 focus:ring-1 focus:ring-emerald-700 transition">
        <input name="no_hp" required placeholder="No HP/WA aktif" value="{{ old('no_hp') }}" class="w-full border border-stone-200 rounded-xl px-4 py-2.5 text-[14px] focus:outline-none focus:border-emerald-700 focus:ring-1 focus:ring-emerald-700 transition">
        <select name="kategori" required class="w-full border border-stone-200 rounded-xl px-4 py-2.5 text-[14px] bg-white focus:outline-none focus:border-emerald-700 focus:ring-1 focus:ring-emerald-700 transition">
          <option value="">— Pilih kategori —</option>
          @foreach($kategoris as $key => $label)
          <option value="{{ $key }}" @selected(old('kategori') === $key)>{{ $label }}</option>
          @endforeach
        </select>
        <textarea name="pesan" required rows="5" placeholder="Tulis pertanyaan Anda..." class="w-full border border-stone-200 rounded-xl px-4 py-2.5 text-[14px] focus:outline-none focus:border-emerald-700 focus:ring-1 focus:ring-emerald-700 transition">{{ old('pesan') }}</textarea>
        @include('partials.turnstile')
        <button class="w-full bg-emerald-950 hover:bg-emerald-800 text-white font-extrabold text-[13px] tracking-widest py-3.5 rounded-xl transition">KIRIM KONSULTASI →</button>
      </form>
    </div>
  </div>
</div>
</div>
@endsection
