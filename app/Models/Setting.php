<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    public static function get(string $key, ?string $default = null): ?string
    {
        // Cache 1 jam: dipanggil 15-20x per halaman (footer, dawuh, kontak).
        try {
            return Cache::remember('setting:'.$key, 3600, function () use ($key, $default) {
                try {
                    $row = static::where('key', $key)->first();
                    if ($row) return $row->value;
                } catch (\Throwable $e) {
                    // tabel belum migrate — fallback ke default agar landing tidak error
                }
                return static::defaults()[$key] ?? $default;
            });
        } catch (\Throwable $e) {
            return static::defaults()[$key] ?? $default;
        }
    }

    public static function set(string $key, ?string $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
        try { Cache::forget('setting:'.$key); } catch (\Throwable $e) {}
    }

    public static function defaults(): array
    {
        return [
            // Umum
            'site_tagline' => 'Tahfidz 30 juz, kitab kuning, sekolah formal MTs-MA, dan pembinaan akhlak 24 jam untuk 500+ santri di Kauman.',
            'site_deskripsi' => "Pondok Pesantren Kunuuzul Imam Kauman — Tahfidzul Qur'an 30 juz, kajian kitab kuning, sekolah formal MTs-MA, dan tarbiyah akhlak 24 jam.",
            // Kontak
            'kontak_alamat_1' => 'ALAMAT ASRAMA 1 (ganti di Pengaturan)',
            'kontak_alamat_2' => 'ALAMAT ASRAMA 2 (ganti di Pengaturan)',
            'kontak_wa_1' => '08xxxxxxxxxx',
            'kontak_wa_2' => '08xxxxxxxxxx',
            'kontak_email' => '',
            'kontak_jam' => 'Senin–Sabtu 08.00–15.00',
            // Footer (full kontrol)
            'footer_deskripsi' => "Pondok Pesantren Ahlussunnah wal Jama'ah. Fokus Tahfidzul Qur'an, Kajian Kitab Kuning, dan Tarbiyah Akhlak.",
            'footer_lembaga_judul' => 'Lembaga',
            'footer_lembaga_isi' => "MADRASAH DINIYAH TASHWIRUL AFKAR|/lembaga\nMA Kunuuzul Imam|https://makunuuzulimamkauman.sch.id/\nSMP Kunuuzul Imam|/lembaga",
            'footer_program_judul' => 'Program Unggulan',
            'footer_program_isi' => "Tahfidzul Qur'an 30 Juz\nMadrasah Diniyah & Kitab Kuning\nMadrasah Formal (MTs-MA)\nBahasa Arab & Inggris",
            'footer_kontak_judul' => 'Kontak',
            'footer_copyright' => 'Pondok Pesantren Kunuuzul Imam Kauman',
            'footer_alamat_bawah' => 'Jl. Kauman No. 123 • Kauman, Jawa Tengah',
            // Profil
            'profil_sejarah_singkat' => 'Pondok Pesantren Kunuuzul Imam Kauman didirikan sebagai wujud khidmah kepada umat di kawasan Kauman. Berawal dari majelis taklim dan halaqah tahfidz kecil, kini berkembang menjadi lembaga pendidikan Islam terpadu dengan jenjang MTs, MA, dan Ma\'had Aly (takhassus kitab).',
            'profil_sejarah_lengkap' => 'Berawal dari majelis taklim dan halaqah tahfidz kecil di Kauman, pondok berkembang menjadi lembaga pendidikan Islam terpadu: Madrasah Diniyah, MTs-MA, dan Ma\'had Aly. Kurikulum memadukan salaf (sorogan, bandongan, hafalan) dan khalaf (sekolah formal, bahasa, teknologi) dengan pengawasan musyrif 24 jam.',
            'profil_sejarah_gambar' => '',
            'profil_visi' => 'Menjadi pondok pesantren unggul dalam tahfidz, ilmu syar\'i, dan akhlak karimah menuju generasi rabbani.',
            'profil_misi' => "1. Menyelenggarakan tahfidz 30 juz bersanad.\n2. Mengkaji kitab kuning secara intensif.\n3. Menyelenggarakan pendidikan formal bermutu.\n4. Membina akhlak dan kemandirian santri 24 jam.",
            'profil_fasilitas' => "Masjid & Aula\nAsrama Putra-Putri\nPerpustakaan\nLab Komputer\nLapangan & Panahan\nDapur & Kantin\nKlinik Santri\nKoperasi",
            // Santri
            'santri_stat_1_angka' => '500', 'santri_stat_1_satuan' => '+', 'santri_stat_1_label' => 'Santri Mukim',
            'santri_stat_2_angka' => '30', 'santri_stat_2_satuan' => 'Juz', 'santri_stat_2_label' => 'Target Tahfidz',
            'santri_stat_3_angka' => '40', 'santri_stat_3_satuan' => '+', 'santri_stat_3_label' => 'Ustadz & Musyrif',
            'santri_stat_4_angka' => '24', 'santri_stat_4_satuan' => 'Jam', 'santri_stat_4_label' => 'Pembinaan',
            'santri_jadwal' => "03.30 | Tahajud & murojaah\n04.30 | Subuh + setoran hafalan\n07.00 | Sekolah formal (MTs-MA)\n15.30 | Sorogan kitab & diniyah\n19.30 | Musyawarah + ekstrakurikuler",
            'santri_tatib' => "Menjaga shalat berjamaah lima waktu\nSetoran hafalan sesuai target halaqah\nBerbahasa Arab/Inggris di area pondok\nIzin musyrif untuk keluar asrama\nMenjaga adab kepada guru & teman",
            'santri_intro' => 'Kehidupan santri berjalan 24 jam dalam pengawasan musyrif asrama.',
            // PSB (Penerimaan Santri Baru) — halaman psb.domain
            'psb_tahun_ajaran' => '2026/2027',
            'psb_status' => 'Dibuka',
            'psb_gelombang' => '',
            'psb_syarat' => "Kartu Keluarga (KK) calon santri\nIjazah atau Surat Keterangan Lulus (SKL)\nKIP / kartu bantuan pendidikan (jika ada)\nNIK calon santri (16 digit)\nNomor HP/WhatsApp aktif orang tua atau wali",
            'psb_biaya_info' => 'Informasi biaya pendidikan dan rincian pembayaran dapat ditanyakan langsung kepada panitia PSB melalui WhatsApp.',
            'psb_narahubung' => '',
            // Dawuh pengasuh (kartu hijau di landing)
            'dawuh_arab' => '﴿ خَيْرُكُمْ مَنْ تَعَلَّمَ الْقُرْآنَ وَعَلَّمَهُ ﴾',
            'dawuh_terjemah' => '“Sebaik-baik kalian adalah yang belajar Al-Qur’an dan mengajarkannya.”',
            'dawuh_sumber' => '— HR. BUKHARI • DAWUH PENGASUH',
            // Join Us (link + jumlah followers)
            'sosmed_twitter_url' => '#', 'sosmed_twitter_count' => '46.8K',
            'sosmed_instagram_url' => '#', 'sosmed_instagram_count' => '62.9K',
            'sosmed_youtube_url' => '#', 'sosmed_youtube_count' => '110.6K',
            'sosmed_tiktok_url' => '#', 'sosmed_tiktok_count' => '50.2K',
            'sosmed_facebook_url' => '#',
            'sosmed_whatsapp_url' => '#',
            // Organisasi
            'organisasi_intro' => 'Organisasi di dalam pesantren dan di luar pesantren yang masih berada di bawah naungan Pondok Pesantren Kunuuzul Imam Kauman.',
            'organisasi_dalam' => 'FOKUS|Forum Komunikasi Santri|Wadah komunikasi dan aspirasi santri — latihan kepemimpinan, musyawarah, serta khidmat dalam kehidupan pondok sehari-hari.',
            'organisasi_luar' => "IASKA|Ikatan Alumni Santri Kauman|Wadah silaturahmi dan pengabdian alumni — menjaga sanad, khidmat, dan kontribusi untuk pondok serta masyarakat.\nHMASKI|Harokah Mahasiswa Alumni Kauman|Pergerakan mahasiswa alumni Kauman — kajian, kaderisasi, dan pengabdian di kampus maupun masyarakat.\nGARDA|Garis Depan|Garda terdepan khidmat dan pengabdian — siap siaga mendukung kegiatan dan program pondok.",
        ];
    }

    /**
     * Kategori / sub menu untuk halaman admin Pengaturan.
     * key grup => [label, icon (fontawesome), desc, fields: key => [label, type, hint]]
     */
    public static function groups(): array
    {
        return [
            'umum' => [
                'label' => 'Umum',
                'icon' => 'fa-solid fa-house',
                'desc' => 'Tagline & identitas singkat website.',
                'fields' => [
                    'site_tagline' => ['label' => 'Tagline Website', 'type' => 'textarea', 'hint' => 'Tampil di hero landing page.'],
                    'site_deskripsi' => ['label' => 'Deskripsi Situs (SEO)', 'type' => 'textarea', 'hint' => 'Tampil di hasil Google sebagai cuplikan + meta description. Maks ~160 karakter.'],
                ],
            ],
            'footer' => [
                'label' => 'Footer',
                'icon' => 'fa-solid fa-window-maximize',
                'desc' => 'Semua teks di footer bawah (4 kolom + copyright).',
                'fields' => [
                    'footer_deskripsi' => ['label' => 'Deskripsi Footer (kolom 1)', 'type' => 'textarea', 'hint' => 'Teks di bawah logo.'],
                    'footer_lembaga_judul' => ['label' => 'Judul Kolom Lembaga', 'type' => 'text', 'hint' => 'Contoh: Lembaga'],
                    'footer_lembaga_isi' => ['label' => 'Isi Kolom Lembaga', 'type' => 'textarea', 'hint' => 'Satu baris satu link. Format: Label|URL — contoh: MA Kunuuzul Imam|https://...'],
                    'footer_program_judul' => ['label' => 'Judul Kolom Program', 'type' => 'text', 'hint' => 'Contoh: Program Unggulan'],
                    'footer_program_isi' => ['label' => 'Isi Kolom Program', 'type' => 'textarea', 'hint' => 'Satu baris satu item, tanpa link.'],
                    'footer_kontak_judul' => ['label' => 'Judul Kolom Kontak', 'type' => 'text', 'hint' => 'Contoh: Kontak'],
                    'footer_copyright' => ['label' => 'Teks Copyright', 'type' => 'text', 'hint' => '"Copyright © TAHUN" otomatis di depan. Contoh: Pondok Pesantren Kunuuzul Imam Kauman'],
                ],
            ],
            'kontak' => [
                'label' => 'Kontak',
                'icon' => 'fa-solid fa-phone',
                'desc' => 'Alamat & WA asrama + jam — tampil di footer dan halaman kontak.',
                'fields' => [
                    'kontak_alamat_1' => ['label' => 'Alamat Asrama', 'type' => 'text', 'hint' => ''],
                    'kontak_wa_1' => ['label' => 'No. WA Asrama', 'type' => 'text', 'hint' => 'Contoh: 081234567890'],
                    'kontak_jam' => ['label' => 'Jam Pelayanan', 'type' => 'text', 'hint' => ''],
                ],
            ],
            'profil' => [
                'label' => 'Profil',
                'icon' => 'fa-solid fa-mosque',
                'desc' => 'Teks halaman Sejarah, Visi & fasilitas.',
                'fields' => [
                    'profil_sejarah_singkat' => ['label' => 'Sejarah Singkat', 'type' => 'textarea', 'hint' => ''],
                    'profil_sejarah_lengkap' => ['label' => 'Sejarah Lengkap', 'type' => 'textarea', 'hint' => 'Pisahkan paragraf dengan SATU BARIS KOSONG.'],
                    'profil_sejarah_gambar' => ['label' => 'Gambar Sampul Sejarah', 'type' => 'image', 'hint' => 'JPG/PNG/WEBP, maks 4MB. Kosongkan = tanpa sampul.'],
                    'profil_visi' => ['label' => 'Visi', 'type' => 'textarea', 'hint' => ''],
                    'profil_misi' => ['label' => 'Misi', 'type' => 'textarea', 'hint' => 'Satu baris satu poin.'],
                    'profil_fasilitas' => ['label' => 'Fasilitas', 'type' => 'textarea', 'hint' => 'Satu baris satu fasilitas.'],
                ],
            ],
            'santri' => [
                'label' => 'Santri',
                'icon' => 'fa-solid fa-users',
                'desc' => 'Statistik, jadwal, tatib & intro halaman santri.',
                'fields' => [
                    'santri_stat_1_angka' => ['label' => 'Stat 1 Angka', 'type' => 'text', 'hint' => ''],
                    'santri_stat_1_satuan' => ['label' => 'Stat 1 Satuan', 'type' => 'text', 'hint' => 'Contoh: +'],
                    'santri_stat_1_label' => ['label' => 'Stat 1 Label', 'type' => 'text', 'hint' => ''],
                    'santri_stat_2_angka' => ['label' => 'Stat 2 Angka', 'type' => 'text', 'hint' => ''],
                    'santri_stat_2_satuan' => ['label' => 'Stat 2 Satuan', 'type' => 'text', 'hint' => ''],
                    'santri_stat_2_label' => ['label' => 'Stat 2 Label', 'type' => 'text', 'hint' => ''],
                    'santri_stat_3_angka' => ['label' => 'Stat 3 Angka', 'type' => 'text', 'hint' => ''],
                    'santri_stat_3_satuan' => ['label' => 'Stat 3 Satuan', 'type' => 'text', 'hint' => ''],
                    'santri_stat_3_label' => ['label' => 'Stat 3 Label', 'type' => 'text', 'hint' => ''],
                    'santri_stat_4_angka' => ['label' => 'Stat 4 Angka', 'type' => 'text', 'hint' => ''],
                    'santri_stat_4_satuan' => ['label' => 'Stat 4 Satuan', 'type' => 'text', 'hint' => ''],
                    'santri_stat_4_label' => ['label' => 'Stat 4 Label', 'type' => 'text', 'hint' => ''],
                    'santri_jadwal' => ['label' => 'Jadwal Harian', 'type' => 'textarea', 'hint' => 'Satu baris satu jadwal.'],
                    'santri_tatib' => ['label' => 'Tata Tertib', 'type' => 'textarea', 'hint' => 'Satu baris satu aturan.'],
                    'santri_intro' => ['label' => 'Intro Santri', 'type' => 'textarea', 'hint' => ''],
                ],
            ],
            'dawuh' => [
                'label' => 'Dawuh Pengasuh',
                'icon' => 'fa-solid fa-quote-left',
                'desc' => 'Kartu hijau dawuh di landing page.',
                'fields' => [
                    'dawuh_arab' => ['label' => 'Teks Arab', 'type' => 'text', 'hint' => ''],
                    'dawuh_terjemah' => ['label' => 'Terjemah', 'type' => 'textarea', 'hint' => ''],
                    'dawuh_sumber' => ['label' => 'Sumber', 'type' => 'text', 'hint' => 'Contoh: — HR. BUKHARI'],
                ],
            ],
            'sosmed' => [
                'label' => 'Sosial Media',
                'icon' => 'fa-solid fa-share-nodes',
                'desc' => 'Link & jumlah followers tombol Join Us.',
                'fields' => [
                    'sosmed_twitter_url' => ['label' => 'Twitter URL', 'type' => 'text', 'hint' => ''],
                    'sosmed_twitter_count' => ['label' => 'Twitter Followers', 'type' => 'text', 'hint' => ''],
                    'sosmed_instagram_url' => ['label' => 'Instagram URL', 'type' => 'text', 'hint' => ''],
                    'sosmed_instagram_count' => ['label' => 'Instagram Followers', 'type' => 'text', 'hint' => ''],
                    'sosmed_youtube_url' => ['label' => 'Youtube URL', 'type' => 'text', 'hint' => ''],
                    'sosmed_youtube_count' => ['label' => 'Youtube Subscribers', 'type' => 'text', 'hint' => ''],
                    'sosmed_tiktok_url' => ['label' => 'Tiktok URL', 'type' => 'text', 'hint' => 'Dipakai juga untuk dock 3D di footer.'],
                    'sosmed_tiktok_count' => ['label' => 'Tiktok Followers', 'type' => 'text', 'hint' => ''],
                    'sosmed_facebook_url' => ['label' => 'Facebook URL', 'type' => 'text', 'hint' => 'Dipakai untuk dock 3D di footer.'],
                    'sosmed_whatsapp_url' => ['label' => 'WhatsApp URL', 'type' => 'text', 'hint' => 'Contoh: https://wa.me/6281234567890 — dipakai dock 3D footer. Kosongkan (#) untuk otomatis dari no. kontak.'],
                ],
            ],
            'organisasi' => [
                'label' => 'Organisasi',
                'icon' => 'fa-solid fa-sitemap',
                'desc' => 'Isi halaman Organisasi: intro + daftar organisasi dalam & luar pesantren.',
                'fields' => [
                    'organisasi_intro' => ['label' => 'Intro Halaman', 'type' => 'textarea', 'hint' => 'Paragraf pembuka di bawah judul.'],
                    'organisasi_dalam' => ['label' => 'Organisasi Dalam Pesantren', 'type' => 'textarea', 'hint' => 'Satu baris satu organisasi. Format: SINGKATAN|Nama Panjang|Deskripsi — contoh: FOKUS|Forum Komunikasi Santri|Wadah aspirasi santri.'],
                    'organisasi_luar' => ['label' => 'Organisasi Luar (Naungan Pondok)', 'type' => 'textarea', 'hint' => 'Format sama: SINGKATAN|Nama Panjang|Deskripsi, satu baris satu organisasi.'],
                ],
            ],
        ];
    }
}
