@extends('layouts.app')
@section('title', 'Pendaftaran Berhasil')
@section('content')
<div class="max-w-xl mx-auto px-4 py-14 text-center">
  <div class="bg-white rounded-3xl shadow p-8">
    <div class="text-6xl">✅</div>
    <h1 class="text-2xl font-extrabold text-primary-900 mt-3">Pendaftaran Berhasil!</h1>
    <p class="text-sm text-stone-500 mt-2">Jazakumullah khairan, {{ $pendaftaran->nama_lengkap }}. Simpan nomor pendaftaran berikut:</p>
    <p class="text-2xl font-extrabold bg-primary-50 text-primary-800 rounded-xl py-3 mt-4">{{ $pendaftaran->nomor_pendaftaran }}</p>
    <p class="text-sm mt-4">Langkah selanjutnya: hubungi panitia via WA <b>0812-3456-7890</b> dengan mengirim nomor pendaftaran + foto KK untuk verifikasi.</p>
    <a href="{{ route('home') }}" class="inline-block mt-5 bg-primary-700 text-white px-6 py-2 rounded-full font-bold">Kembali ke Beranda</a>
  </div>
</div>
@endsection
