<?php

namespace Database\Seeders;

use App\Models\HeroSlide;
use App\Models\Lembaga;
use App\Models\Pengasuh;
use App\Models\Setting;
use Illuminate\Database\Seeder;

class KontenLandingSeeder extends Seeder
{
    public function run(): void
    {
        foreach (Setting::defaults() as $key => $val) {
            Setting::updateOrCreate(['key' => $key], ['value' => $val]);
        }

        if (HeroSlide::count() === 0) {
            $slides = [
                ['label' => 'TAHFIDZ QURAN', 'badge' => '📖 Tahfidz • Sanad 30 Juz', 'title' => "HAFAL QUR'AN,<br>SAMPAI TUJUAN.", 'sub' => 'Tahfidz 30 juz, tasmi harian, munaqasyah tiap semester. Target khatam bersanad.', 'desc' => 'Setoran harian & sanad 30 juz', 'gambar' => '/images/hero/slide-1.jpg', 'cta1_text' => 'DAFTAR TAHFIDZ', 'cta1_url' => '/ppdb', 'cta2_text' => 'PROFIL PONDOK', 'cta2_url' => '/profil', 'urutan' => 0],
                ['label' => 'KITAB KUNING', 'badge' => '📚 Diniyah • Sorogan Kitab', 'title' => "ALIM KITAB,<br>AKHLAK MULIA.", 'sub' => 'Nahwu, Shorof, Fiqh, Tauhid, Tafsir. Kajian bandongan + sorogan setiap hari.', 'desc' => 'Sorogan & bandongan intensif', 'gambar' => '/images/hero/slide-2.jpg', 'cta1_text' => 'LIHAT PROGRAM', 'cta1_url' => '/program', 'cta2_text' => 'KONTAK USTADZ', 'cta2_url' => '/kontak', 'urutan' => 1],
                ['label' => 'SEKOLAH FORMAL', 'badge' => '🏫 MTs-MA • Bilingual', 'title' => "ILMU DUNIA,<br>BEKAL AKHIRAT.", 'sub' => 'Ijazah Kemenag + bahasa Arab-Inggris intensif, IT, dan olimpiade.', 'desc' => 'MTs-MA + bilingual', 'gambar' => '/images/hero/slide-3.jpg', 'cta1_text' => 'DAFTAR PPDB', 'cta1_url' => '/ppdb', 'cta2_text' => 'GALERI SANTRI', 'cta2_url' => '/galeri', 'urutan' => 2],
                ['label' => 'ASRAMA & TARBIYAH', 'badge' => '🌙 Tarbiyah • Kemandirian', 'title' => "BETAH MONDOK,<br>HATI TENANG.", 'sub' => 'Asrama nyaman, panahan, pramuka, kaligrafi, tahsin, dan klinik santri.', 'desc' => 'Musyrif 24 jam • Ekstrakurikuler', 'gambar' => '/images/hero/slide-4.jpg', 'cta1_text' => 'DAFTAR SEKARANG', 'cta1_url' => '/ppdb', 'cta2_text' => 'BACA BERITA', 'cta2_url' => '/berita', 'urutan' => 3],
            ];
            foreach ($slides as $s) {
                HeroSlide::create($s + ['is_aktif' => true]);
            }
        }

        if (Lembaga::count() === 0) {
            $items = [
                ['nama' => 'MADRASAH DINIYAH TASHWIRUL AFKAR', 'deskripsi' => 'Madrasah Diniyah: kajian kitab kuning — Nahwu, Shorof, Fiqh, Tauhid, dan Tafsir.', 'ikon' => '🕌', 'urutan' => 0],
                ['nama' => 'MA Kunuuzul Imam', 'deskripsi' => 'Madrasah Aliyah: pendidikan formal setingkat SMA, bilingual Arab-Inggris, dan pembinaan olimpiade.', 'ikon' => '🎓', 'urutan' => 1],
                ['nama' => 'SMP Kunuuzul Imam', 'deskripsi' => 'Sekolah Menengah Pertama: kurikulum nasional + kepesantrenan dan tahfidz harian.', 'ikon' => '🏫', 'urutan' => 2],
                ['nama' => 'Tahfidzul Qur’an', 'deskripsi' => 'Hafalan 30 juz bersanad, tasmi harian, dan munaqasyah tiap semester.', 'ikon' => '📖', 'urutan' => 3],
                ['nama' => 'Bahasa Intensif', 'deskripsi' => 'Program Arab & Inggris dengan pembiasaan 24 jam.', 'ikon' => '🗣️', 'urutan' => 4],
                ['nama' => 'Tahsin & Qira’ah', 'deskripsi' => 'Perbaikan makhraj, tajwid, dan seni baca Al-Qur’an.', 'ikon' => '🎙️', 'urutan' => 5],
            ];
            foreach ($items as $it) {
                Lembaga::create($it + ['is_aktif' => true]);
            }
        }

        if (Pengasuh::count() === 0) {
            $items = [
                ['nama' => 'KH. Imam Kauman', 'jabatan' => 'Pengasuh — Pengampu Tafsir & Tasawuf', 'urutan' => 0],
                ['nama' => 'Ust. Ahmad Fauzi, Lc.', 'jabatan' => 'Mudir Ma’had — Tahfidz', 'urutan' => 1],
                ['nama' => 'Ustzh. Maryam Hafizhah', 'jabatan' => 'Musyrifah Putri — Tahsin', 'urutan' => 2],
                ['nama' => 'Ust. Bilal Syah, M.Pd.', 'jabatan' => 'Kepala MTs-MA — Bahasa', 'urutan' => 3],
            ];
            foreach ($items as $it) {
                Pengasuh::create($it + ['is_aktif' => true]);
            }
        }
    }
}
