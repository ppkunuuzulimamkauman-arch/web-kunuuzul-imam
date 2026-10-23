@extends('admin.layout')
@section('title','Panduan Landing')
@section('crumb','Peta kontrol: bagian landing → menu adminnya')
@section('content')
<div class="card">
  <p style="font-size:13.5px;color:var(--muted);margin-bottom:12px">Semua bagian beranda dikontrol dari sini. Tulis/edit dari menu yang ditautkan, landing ikut berubah otomatis. Bagian yang datanya masih kosong otomatis menampilkan contoh bawaan.</p>
  <div style="overflow-x:auto"><table>
    <tr><th>Bagian di landing</th><th>Dikontrol dari</th><th>Cara</th></tr>
    <tr><td><b>Sorotan Utama (maks 4 berita)</b></td><td><a class="link" href="{{ route('admin.berita.index') }}">Berita & Artikel</a></td><td>Klik ⭐ pada berita publish — maks 4 berita aktif dan tampil bergantian di slider, tanpa bintang = otomatis berita terbaru</td></tr>
    <tr><td><b>Linimasa, Cerita, Berita, Feature, Terbaru, Rekomendasi, Populer, Ticker</b></td><td><a class="link" href="{{ route('admin.berita.index') }}">Berita & Artikel</a></td><td>Tulis berita + upload gambar sampul, pilih <span class="pill p-blue">publish</span>, kategori Berita/Acara/PPDB/Prestasi/Pengumuman (bukan Maklumat/Dawuh/Bahtsul/Artikel). Linimasa = 3 terbaru setelah Sorotan.</td></tr>
    <tr><td><b>Maklumat Pondok</b></td><td><a class="link" href="{{ route('admin.berita.index') }}">Berita & Artikel</a></td><td>Kategori = <span class="pill p-gold">Maklumat Pondok</span></td></tr>
    <tr><td><b>Dawuh Masyayikh</b></td><td><a class="link" href="{{ route('admin.berita.index') }}">Berita & Artikel</a></td><td>Kategori = <span class="pill p-gold">Dawuh Masyayikh</span></td></tr>
    <tr><td><b>Bahtsul Masail</b></td><td><a class="link" href="{{ route('admin.berita.index') }}">Berita & Artikel</a></td><td>Kategori = <span class="pill p-gold">Bahtsul Masail</span></td></tr>
    <tr><td><b>Majelis Terdekat & Agenda Pondok</b></td><td><a class="link" href="{{ route('admin.agenda.index') }}">Agenda</a></td><td>Tambah agenda: judul + tanggal + tempat</td></tr>
    <tr><td><b>Galeri Santri</b></td><td><a class="link" href="{{ route('admin.galeri.index') }}">Galeri</a></td><td>Upload foto, otomatis tampil 6 terbaru</td></tr>
    <tr><td><b>Kartu Dawuh Pengasuh</b></td><td><a class="link" href="{{ route('admin.setting.index') }}">Pengaturan Web</a></td><td>Isi <i>dawuh_arab, dawuh_terjemah, dawuh_sumber</i></td></tr>
    <tr><td><b>Join Us (link + followers)</b></td><td><a class="link" href="{{ route('admin.setting.index') }}">Pengaturan Web</a></td><td>Isi <i>sosmed_*_url</i> dan <i>sosmed_*_count</i></td></tr>
    <tr><td><b>Jelajahi Topik</b></td><td><i>Otomatis</i></td><td>Muncul sendiri dari kategori berita yang ada</td></tr>
    <tr><td><b>Foto-foto berita di landing</b></td><td><i>Otomatis</i></td><td>Mengikuti gambar sampul tiap berita</td></tr>
  </table></div>
</div>
@endsection
