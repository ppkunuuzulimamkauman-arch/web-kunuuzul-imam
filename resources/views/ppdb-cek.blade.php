@extends('layouts.app')
@section('title', 'Cek Status Pendaftaran')
@section('content')
<div class="bg-white">
<div class="max-w-xl mx-auto px-4 py-8 md:py-10">
  <p class="text-[12px] font-bold text-stone-400 tracking-wider"><a href="{{ route('home') }}" class="hover:text-orange-600 transition">Beranda</a> <span class="mx-1">/</span> <a href="{{ route('ppdb.form') }}" class="hover:text-orange-600 transition">PSB</a> <span class="mx-1">/</span> <span class="text-stone-600">Cek Status</span></p>
  <h1 class="font-extrabold text-stone-900 text-[26px] md:text-[30px] leading-tight mt-1.5">Cek Status Pendaftaran</h1>
  <div class="w-16 h-1 bg-orange-400 rounded-full mt-3 mb-6"></div>

  <form action="{{ route('ppdb.cek') }}" method="GET" class="border border-stone-200 rounded-2xl p-5 bg-white shadow-sm flex gap-2">
    <input name="nomor" value="{{ old('nomor', $nomor) }}" required maxlength="30" placeholder="Contoh: PPDB-2026-0001" class="flex-1 border border-stone-200 rounded-xl px-4 py-2.5 text-[14px] focus:outline-none focus:border-emerald-700 focus:ring-1 focus:ring-emerald-700 transition">
    <button class="bg-emerald-950 hover:bg-emerald-800 text-white font-extrabold text-[12.5px] tracking-widest px-5 rounded-xl transition">CEK</button>
  </form>

  @if($dicari)
    @if($pendaftaran)
      <div class="border border-stone-200 rounded-2xl p-5 mt-4 bg-stone-50">
        <p class="text-[12px] font-bold tracking-widest text-stone-400">NOMOR PENDAFTARAN</p>
        <p class="font-extrabold text-emerald-950 text-[20px]">{{ $pendaftaran->nomor_pendaftaran }}</p>
        <dl class="mt-3 space-y-2 text-[14px] text-stone-600">
          <div class="flex justify-between gap-4"><dt>Nama</dt><dd class="font-bold text-stone-900 text-right">{{ $pendaftaran->nama_lengkap }}</dd></div>
          <div class="flex justify-between gap-4"><dt>Jenjang</dt><dd class="font-bold text-stone-900 text-right">{{ $pendaftaran->jenjang }}</dd></div>
          <div class="flex justify-between gap-4"><dt>Tanggal daftar</dt><dd class="font-bold text-stone-900 text-right">{{ $pendaftaran->created_at?->format('d M Y') }}</dd></div>
          <div class="flex justify-between gap-4 items-center"><dt>Status</dt><dd><span class="inline-block text-[12px] font-extrabold tracking-widest px-3 py-1 rounded-full {{ $pendaftaran->status === 'diterima' ? 'bg-green-100 text-green-800' : ($pendaftaran->status === 'ditolak' ? 'bg-red-100 text-red-800' : 'bg-amber-100 text-amber-800') }}">{{ strtoupper($pendaftaran->status) }}</span></dd></div>
        </dl>
        <p class="text-[12px] text-stone-400 mt-3">Jika status DITERIMA, hubungi panitia untuk verifikasi berkas & daftar ulang.</p>
      </div>
    @else
      <div class="bg-red-50 border border-red-200 text-red-700 rounded-2xl p-4 text-sm mt-4">Nomor <b>{{ $nomor }}</b> tidak ditemukan. Periksa kembali penulisan nomor pendaftaran Anda.</div>
    @endif
  @endif
</div>
</div>
@endsection
