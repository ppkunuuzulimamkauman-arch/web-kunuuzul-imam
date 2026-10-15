@extends('layouts.app')
@section('title', 'Kebijakan Privasi')
@section('content')
<div class="bg-white">
<div class="max-w-3xl mx-auto px-4 py-8 md:py-10">
  <p class="text-[12px] font-bold text-stone-400 tracking-wider"><a href="{{ route('home') }}" class="hover:text-orange-600 transition">Beranda</a> <span class="mx-1">/</span> <span class="text-stone-600">Privasi</span></p>
  <h1 class="font-extrabold text-stone-900 text-[26px] md:text-[30px] leading-tight mt-1.5">Kebijakan Privasi</h1>
  <div class="w-16 h-1 bg-orange-400 rounded-full mt-3 mb-6"></div>
  <div class="space-y-4 text-[14.5px] leading-relaxed text-stone-600">
    <p>Kami mengumpulkan data yang Anda kirim via formulir PPDB, kontak, dan konsultasi (nama, NIK, kontak, berkas) hanya untuk keperluan layanan pendidikan pondok: verifikasi pendaftaran, komunikasi panitia, dan arsip internal.</p>
    <p>Data tidak diperjualbelikan. Berkas tersimpan di server kami dengan akses terbatas admin. Anda dapat meminta perbaikan/penghapusan data via kontak resmi pondok.</p>
    <p>Formulir dilindungi honeypot, rate-limit, dan Cloudflare Turnstile (jika dikonfigurasi). Log teknis dasar (waktu akses, IP) dapat tersimpan untuk keamanan.</p>
    <p>Pertanyaan privasi: hubungi admin via halaman <a class="font-bold text-emerald-800 hover:underline" href="{{ route('kontak') }}">Kontak</a>.</p>
  </div>
</div>
</div>
@endsection
