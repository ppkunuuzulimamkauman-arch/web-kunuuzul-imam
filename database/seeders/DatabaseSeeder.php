<?php

namespace Database\Seeders;

use App\Models\Berita;
use App\Models\Galeri;
use App\Models\Program;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([AdminSeeder::class, KontenLandingSeeder::class, BeritaSampleSeeder::class]);

        $programs = [
            ['nama' => "Tahfidzul Qur'an 30 Juz", 'kategori' => 'unggulan', 'deskripsi_singkat' => 'Hafalan 30 juz bersanad dengan tasmi harian.', 'deskripsi' => 'Program tahfidz dengan target khatam 30 juz, tasmi harian, munaqasyah tiap semester, dan sanad ke ulama.', 'urutan' => 1],
            ['nama' => 'Madrasah Diniyah & Kitab Kuning', 'kategori' => 'diniyah', 'deskripsi_singkat' => 'Sorogan & bandongan Nahwu, Fiqh, Tauhid, Akhlak.', 'deskripsi' => 'Kajian kitab: Jurumiyah, Imrithi, Fathul Qorib, Fathul Muin, Tafsir Jalalain, Riyadhus Shalihin.', 'urutan' => 2],
            ['nama' => 'MTs-MA Unggulan', 'kategori' => 'formal', 'deskripsi_singkat' => 'Ijazah formal Kemenag + kurikulum pondok.', 'deskripsi' => 'Sekolah formal dengan nilai akademik unggul, bilingual Arab-Inggris, dan pembinaan olimpiade.', 'urutan' => 3],
            ['nama' => 'Bahasa Arab & Inggris Intensif', 'kategori' => 'bahasa', 'deskripsi_singkat' => 'Mahir percakapan 6 bulan dengan bi-ah lughah.', 'deskripsi' => 'Pembiasaan bahasa 24 jam, mahadalah, muhadatsah, dan native speaker.', 'urutan' => 4],
            ['nama' => 'Tahsin & Qiraah Sabuah', 'kategori' => 'tahsin', 'deskripsi_singkat' => 'Perbaikan makhraj, tajwid & seni baca.', 'deskripsi' => 'Tahsin bersanad, qiraah sabuah, dan pembinaan MTQ.', 'urutan' => 5],
            ['nama' => 'Life Skill, IT & Kemandirian', 'kategori' => 'skill', 'deskripsi_singkat' => 'Kaligrafi, desain, komputer & wirausaha.', 'deskripsi' => 'Ekstrakurikuler panahan, pramuka, kaligrafi, multimedia, dan kewirausahaan santri.', 'urutan' => 6],
        ];
        foreach ($programs as $p) {
            Program::firstOrCreate(['slug' => \Str::slug($p['nama'])], $p + ['slug' => \Str::slug($p['nama']), 'is_aktif' => true]);
        }

        $beritas = [
            ['judul' => 'Wisuda Tahfidz Angkatan XII: 18 Santri Khatam 30 Juz', 'kategori' => 'acara', 'excerpt' => 'Momen haru wisuda 120 santri, 18 di antaranya khatam 30 juz.', 'konten' => 'Alhamdulillah wisuda tahfidz berjalan khidmat. Para wisudawan diuji munaqasyah satu juz sekali duduk. Pengasuh berpesan menjaga hafalan dengan murojaah dan mengamalkan Al-Quran.'],
            ['judul' => 'PPDB 2026/2027 Resmi Dibuka, Kuota 200 Santri', 'kategori' => 'ppdb', 'excerpt' => 'Gelombang 1 dibuka hingga Januari, daftar online lebih mudah.', 'konten' => 'Penerimaan santri baru tahun ajaran 2026/2027 dibuka. Calon santri mengisi formulir online, verifikasi WA, lalu tes lisan (bacaan Quran) dan wawancara wali. Tersedia beasiswa tahfidz dan yatim.'],
            ['judul' => 'Santri Raih Juara 1 MTQ Kabupaten', 'kategori' => 'prestasi', 'excerpt' => 'Ananda Fulan meraih juara tahfidz 10 juz tingkat kabupaten.', 'konten' => 'Barakallah, santri kami meraih juara 1 tahfidz 10 juz. Ini buah dari kesabaran murojaah dan bimbingan musyrif. Semoga menjadi motivasi santri lain.'],
        ];
        foreach ($beritas as $b) {
            Berita::firstOrCreate(['slug' => \Str::slug($b['judul'])], $b + ['slug' => \Str::slug($b['judul']), 'penulis' => 'Admin', 'is_published' => true, 'published_at' => now()]);
        }

        foreach (['Haflah Akhirussanah', 'Ziarah Auliya', 'Outbound Santri', 'Khataman Kitab', 'Buka Bersama', 'Pelatihan Panahan'] as $j) {
            Galeri::firstOrCreate(['judul' => $j], ['judul' => $j, 'kategori' => 'kegiatan']);
        }
    }
}
