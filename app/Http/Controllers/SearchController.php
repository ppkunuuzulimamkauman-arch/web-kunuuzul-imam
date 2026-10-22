<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\Lembaga;
use App\Models\Organisasi;
use App\Models\Pengasuh;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function __invoke(Request $request): View
    {
        $q = trim((string) $request->query('q', ''));
        $q = Str::limit(preg_replace('/\s+/', ' ', $q) ?? $q, 100, '');

        $groups = collect();

        if ($q !== '') {
            $like = '%'.$q.'%';

            $berita = Berita::query()
                ->where('is_published', true)
                ->where(function ($query) use ($like) {
                    $query->where('judul', 'like', $like)
                        ->orWhere('excerpt', 'like', $like)
                        ->orWhere('konten', 'like', $like);
                })
                ->latest('published_at')
                ->take(10)
                ->get()
                ->map(fn (Berita $item) => [
                    'title' => $item->judul,
                    'description' => Str::limit(strip_tags((string) ($item->excerpt ?: $item->konten)), 180),
                    'meta' => trim(($item->published_at?->translatedFormat('d F Y') ?? '').' • '.ucfirst($item->kategori ?? 'Berita'), ' •'),
                    'url' => route('berita.show', $item->slug),
                ]);

            $program = Program::query()
                ->where('is_aktif', true)
                ->where(function ($query) use ($like) {
                    $query->where('nama', 'like', $like)
                        ->orWhere('deskripsi_singkat', 'like', $like)
                        ->orWhere('deskripsi', 'like', $like);
                })
                ->orderBy('urutan')
                ->take(10)
                ->get()
                ->map(fn (Program $item) => [
                    'title' => $item->nama,
                    'description' => Str::limit(strip_tags((string) ($item->deskripsi_singkat ?: $item->deskripsi)), 180),
                    'meta' => 'Program • '.ucfirst($item->kategori ?? 'Program'),
                    'url' => route('program.show', $item->slug),
                ]);

            $organisasi = Organisasi::query()
                ->where('is_aktif', true)
                ->where(function ($query) use ($like) {
                    $query->where('nama', 'like', $like)
                        ->orWhere('singkatan', 'like', $like)
                        ->orWhere('deskripsi_singkat', 'like', $like)
                        ->orWhere('deskripsi', 'like', $like);
                })
                ->orderBy('urutan')
                ->take(10)
                ->get()
                ->map(fn (Organisasi $item) => [
                    'title' => $item->nama,
                    'description' => Str::limit(strip_tags((string) ($item->deskripsi_singkat ?: $item->deskripsi)), 180),
                    'meta' => 'Organisasi',
                    'url' => route('organisasi.show', $item->slug),
                ]);

            $lembaga = Lembaga::query()
                ->where('is_aktif', true)
                ->where(function ($query) use ($like) {
                    $query->where('nama', 'like', $like)
                        ->orWhere('deskripsi', 'like', $like);
                })
                ->orderBy('urutan')
                ->take(10)
                ->get()
                ->map(fn (Lembaga $item) => [
                    'title' => $item->nama,
                    'description' => Str::limit(strip_tags((string) $item->deskripsi), 180),
                    'meta' => 'Lembaga',
                    'url' => route('lembaga'),
                ]);

            $masyayikh = Pengasuh::query()
                ->where('is_aktif', true)
                ->where(function ($query) use ($like) {
                    $query->where('nama', 'like', $like)
                        ->orWhere('jabatan', 'like', $like)
                        ->orWhere('bio', 'like', $like)
                        ->orWhere('biografi', 'like', $like);
                })
                ->orderBy('urutan')
                ->take(10)
                ->get()
                ->map(fn (Pengasuh $item) => [
                    'title' => $item->nama,
                    'description' => Str::limit(strip_tags((string) ($item->bio ?: $item->biografi)), 180),
                    'meta' => $item->jabatan ?: 'Masyayikh',
                    'url' => route('profil.masyayikh', $item),
                ]);

            $halaman = collect([
                ['title' => 'Profil Pondok', 'description' => 'Sejarah singkat, visi misi, dan struktur pondok pesantren.', 'url' => route('profil'), 'keywords' => 'profil sejarah visi misi struktur pondok'],
                ['title' => 'Sejarah Pondok', 'description' => 'Perjalanan berdirinya Pondok Pesantren Kunuuzul Imam Kauman.', 'url' => route('profil.sejarah'), 'keywords' => 'sejarah profil berdirinya pondok'],
                ['title' => 'Masyayikh', 'description' => 'Profil pengasuh dan dewan masyayikh pondok.', 'url' => route('profil.struktur'), 'keywords' => 'masyayikh pengasuh ustadz kiai struktur'],
                ['title' => 'Visi & Misi', 'description' => 'Visi, misi, dan tujuan pendidikan pondok pesantren.', 'url' => route('profil.visi'), 'keywords' => 'visi misi tujuan profil'],
                ['title' => 'Lembaga Pendidikan', 'description' => 'Madrasah diniyah, sekolah formal, dan lembaga pondok.', 'url' => route('lembaga'), 'keywords' => 'lembaga madrasah diniyah sekolah formal'],
                ['title' => 'Program Pendidikan', 'description' => 'Program unggulan, taklimi, tathbiqi, bahasa, dan keterampilan.', 'url' => route('program.index'), 'keywords' => 'program pendidikan taklimi tathbiqi tahfidz kitab bahasa skill'],
                ['title' => 'Organisasi', 'description' => 'Organisasi di dalam dan di luar pesantren.', 'url' => route('organisasi'), 'keywords' => 'organisasi fokus iaska hmaski garda'],
                ['title' => 'Kehidupan Santri', 'description' => 'Jadwal harian, tata tertib, dan pembinaan santri.', 'url' => route('santri.index'), 'keywords' => 'santri kehidupan asrama jadwal tata tertib'],
                ['title' => 'Galeri', 'description' => 'Dokumentasi kegiatan dan suasana pondok.', 'url' => route('galeri.index'), 'keywords' => 'galeri foto dokumentasi kegiatan'],
                ['title' => 'Penerimaan Santri Baru (PSB)', 'description' => 'Informasi pendaftaran, syarat, dan alur penerimaan santri baru.', 'url' => config('pondok.psb_url'), 'keywords' => 'psb ppdb pendaftaran santri baru penerimaan'],
                ['title' => 'Layanan Surat Online (E-Surat)', 'description' => 'Ajukan dan cek status surat secara online.', 'url' => config('pondok.surat_url'), 'keywords' => 'surat e-surat layanan surat online'],
                ['title' => 'Direktori Alumni', 'description' => 'Cari data alumni berdasarkan NIK.', 'url' => config('pondok.alumni_url'), 'keywords' => 'alumni direktori nik'],
                ['title' => 'Kontak', 'description' => 'Alamat, nomor WhatsApp, dan jam layanan kantor pondok.', 'url' => route('kontak'), 'keywords' => 'kontak alamat whatsapp wa telepon jam kantor'],
                ['title' => 'Kebijakan Privasi', 'description' => 'Kebijakan penggunaan dan perlindungan data pengunjung.', 'url' => route('privasi'), 'keywords' => 'privasi kebijakan data perlindungan'],
            ])->filter(function (array $page) use ($q) {
                $haystack = Str::lower($page['title'].' '.$page['description'].' '.$page['keywords']);

                return Str::contains($haystack, Str::lower($q));
            })->take(10)->map(fn (array $page) => [
                'title' => $page['title'],
                'description' => $page['description'],
                'meta' => 'Halaman',
                'url' => $page['url'],
            ])->values();

            $groups = collect([
                'Berita & Artikel' => $berita,
                'Program' => $program,
                'Organisasi' => $organisasi,
                'Lembaga' => $lembaga,
                'Masyayikh' => $masyayikh,
                'Halaman' => $halaman,
            ])->filter(fn ($items) => $items->isNotEmpty());
        }

        $total = $groups->sum(fn ($items) => $items->count());

        return view('search', compact('q', 'groups', 'total'));
    }
}
