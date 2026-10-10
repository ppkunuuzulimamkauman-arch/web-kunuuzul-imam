<?php

namespace Database\Seeders;

use App\Models\Berita;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Contoh isi agar semua blok landing terisi unik (1 artikel = 1 blok).
 * Aman dijalankan ulang (cek slug). Hapus/edit dari menu Berita & Artikel.
 */
class BeritaSampleSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            // [judul, kategori, excerpt]
            ['Haflah Akhirussanah 1447 H Berlangsung Khidmat', 'acara', 'Rangkaian haflah akhir tahun dimeriahkan penampilan santri dan mauidhah hasanah pengasuh.'],
            ['Pelantikan Pengurus OSMA Masa Khidmah 2026', 'acara', 'Pengurus baru dilantik dan siap mengabdi lewat program kerja kepesantrenan.'],
            ['Peringatan Maulid Nabi di Masjid Jami Kauman', 'acara', 'Shalawat dan mauidhah menghangatkan peringatan maulid bersama warga.'],
            ['Khataman Kitab Fathul Qorib Kelas Ulya', 'acara', 'Santri kelas ulya menuntaskan kajian fiqih dan berdoa bersama masyayikh.'],
            ['Ziarah Auliya: Napak Tilas Ulama Kauman', 'acara', 'Rombongan santri berziarah dan mengambil berkah perjuangan para ulama.'],
            ['Outbound Santri: Melatih Kemandirian di Alam', 'acara', 'Kegiatan luar ruang melatih kerja sama, keberanian, dan kemandirian santri.'],
            ['PPDB Gelombang 2 Dibuka, Tambahan Kuota 100 Santri', 'ppdb', 'Masih ada kesempatan: gelombang kedua dibuka hingga kuota terpenuhi.'],
            ['Beasiswa Tahfidz untuk 20 Santri Berprestasi', 'ppdb', 'Program beasiswa penuh bagi penghafal dengan target dan akhlak terbaik.'],
            ['Jadwal Tes PPDB: Baca Quran dan Wawancara Wali', 'ppdb', 'Calon santri mengikuti tes lisan bacaan Quran dan wawancara bersama wali.'],
            ['Santri Raih Juara 2 MHQ Tingkat Provinsi', 'prestasi', 'Tilawah dan hafalan ananda menembus final dan meraih juara dua provinsi.'],
            ['Tim Olimpiade MTs Lolos Final Sains', 'prestasi', 'Dua santri lolos final olimpiade sains setelah pembinaan intensif.'],
            ['Juara Harapan 1 Pidato Bahasa Arab', 'prestasi', 'Pidato lughoh ananda memukau dewan juri di ajang antar pesantren.'],
            ['Kontingen Pramuka Raih Regu Tergiat 1', 'prestasi', 'Kedisiplinan dan kekompakan mengantar regu pramuka jadi yang tergiat.'],
            ['Ekstrakurikuler Panahan Raih Juara 3', 'prestasi', 'Sunnah memanah berbuah prestasi di kejuaraan tingkat kabupaten.'],
            ['Pengumuman Libur Akhir Semester Ganjil', 'pengumuman', 'Jadwal libur, kepulangan, dan kembali ke pondok semester ganjil.'],
            ['Jadwal Masuk Kembali Setelah Libur Maulid', 'pengumuman', 'Santri diharapkan kembali tepat waktu dengan membawa perlengkapan lengkap.'],
            ['Klinik Santri Buka Layanan Kesehatan Gratis', 'pengumuman', 'Pemeriksaan kesehatan rutin kini tersedia setiap pekan di klinik pondok.'],
            ['Penerimaan Rapor dan Evaluasi Semester', 'pengumuman', 'Wali diundang mengambil rapor sekaligus evaluasi perkembangan santri.'],
            ['Kajian Rutin Tafsir Jalalain Setiap Jumat', 'berita', 'Halaqah tafsir ba’da maghrib terbuka untuk santri dan masyarakat umum.'],
            ['Peresmian Gedung Asrama Putri Baru', 'berita', 'Asrama baru dua lantai siap menampung 120 santriwati dengan fasilitas lengkap.'],
            ['Pelatihan Kaligrafi untuk Santri Baru', 'berita', 'Seni menulis indah Arab dilatihkan sebagai bekal keterampilan santri.'],
            ['Panen Raya Kebun Santri: Panen Melon', 'berita', 'Hasil belajar tani santri dipanen dan dibagikan ke dapur umum pondok.'],
            ['Kunjungan Silaturahmi Alumni Angkatan 2010', 'berita', 'Alumni pulang kampung, berbagi pengalaman dan motivasi untuk adik kelas.'],
            ['Donor Darah Bersama PMI: 80 Kantong', 'berita', 'Aksi sosial santri dan warga terkumpul 80 kantong darah untuk sesama.'],
            ['Pelatihan Desain dan Multimedia Santri', 'berita', 'Santri belajar desain, fotografi, dan editing untuk dakwah digital.'],
            ['Musyawarah Kubro Pengurus Harian Pondok', 'berita', 'Evaluasi program dan rencana kerja semester dibahas bersama masyayikh.'],
            ['Sarasehan Wali Santri Semester Genap', 'acara', 'Silaturahmi dan arahan pengasuh kepada wali santri semester genap.'],
            ['Pentas Seni dan Bazar Santri Meriahkan Haflah', 'acara', 'Kreativitas santri tampil lewat pentas seni dan bazar kewirausahaan.'],
            ['Kajian Kitab Riyadhus Shalihin Ba’da Maghrib', 'berita', 'Kajian akhlak rutin menguatkan adab santri dalam keseharian.'],
            ['Gotong Royong Bersihkan Lingkungan Pondok', 'berita', 'Jumat bersih: seluruh santri bergotong royong merawat lingkungan.'],
            ['Kajian Tarikh al-Hawadits: Awal Tahun Baru', 'berita', 'Membuka tahun dengan menengok sejarah dan mengambil ibrah perjuangan.'],
            ['Lomba MTQ Internal: 50 Peserta Ikut Serta', 'prestasi', 'Seleksi tilawah internal menjaring qari terbaik untuk pembinaan lanjut.'],
            // Kategori khusus blok landing
            ['Jadwal Kegiatan Ramadhan 1448 H', 'maklumat', 'Rangkaian tarawih, tadarus, dan buka bersama selama bulan suci.'],
            ['Tata Tertib Asrama Baru Berlaku', 'maklumat', 'Penyesuaian jam malam dan izin keluar untuk ketertiban bersama.'],
            ['Libur Kunjungan Wali Santri', 'maklumat', 'Jadwal kunjungan diliburkan sementara selama ujian akhir semester.'],
            ['Dawuh Pengasuh: Adab Sebelum Ilmu', 'dawuh', 'Nasihat pengasuh tentang mendahulukan adab sebelum memperbanyak ilmu.'],
            ['Nasihat Awal Tahun Ajaran Baru', 'dawuh', 'Bekal ruhiyah memasuki tahun ajaran: niat, adab, dan istiqamah.'],
            ['Pentingnya Murojaah Harian', 'dawuh', 'Menjaga hafalan dengan setoran rutin dan mengulang setiap hari.'],
            ['Keputusan Bahtsul Masail: Zakat Profesi', 'bahtsul', 'Hasil musyawarah tentang hukum dan cara menunaikan zakat profesi.'],
            ['Hukum Shalat Jumat bagi Musafir', 'bahtsul', 'Kajian fikih tentang kewajiban Jumat dalam perjalanan jauh.'],
            ['Tanya Jawab: Fidyah Puasa', 'bahtsul', 'Siapa yang wajib fidyah dan bagaimana takarannya menurut kitab.'],
            ['Keutamaan Menuntut Ilmu di Pesantren', 'artikel', 'Telaah singkat keutamaan ilmu dan adab penuntutnya.'],
            ['Adab Santri kepada Kiai', 'artikel', 'Bagian dari kunci keberkahan ilmu: memuliakan guru dan pengasuh.'],
        ];

        foreach ($rows as $i => [$judul, $kategori, $excerpt]) {
            $slug = Str::slug($judul);
            if (Berita::where('slug', $slug)->exists()) {
                continue;
            }
            Berita::create([
                'judul' => $judul,
                'slug' => $slug,
                'kategori' => $kategori,
                'excerpt' => $excerpt,
                'konten' => $excerpt.' Konten lengkap dapat dilengkapi redaksi melalui menu Berita & Artikel di panel admin.',
                'penulis' => 'Redaksi Kauman',
                'is_published' => true,
                'published_at' => now()->subDays($i * 2 + 1),
            ]);
        }
    }
}
