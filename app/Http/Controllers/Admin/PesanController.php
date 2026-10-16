<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Galeri;
use App\Models\HeroSlide;
use App\Models\Lembaga;
use App\Models\Pendaftaran;
use App\Models\Pesan;
use App\Models\Pengasuh;
use App\Models\Program;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PesanController extends Controller
{
    public function index(): View
    {
        return view('admin.pesan.index', [
            'pesans' => Pesan::latest()->paginate(12),
            'belumDibaca' => Pesan::where('is_dibaca', false)->count(),
        ]);
    }

    public function show(Pesan $pesan): View
    {
        $pesan->update(['is_dibaca' => true]);
        $kategoris = ['berita' => 'Berita', 'artikel' => 'Artikel', 'dawuh' => 'Dawuh Masyayikh', 'bahtsul' => 'Bahtsul Masail', 'pengumuman' => 'Pengumuman'];
        return view('admin.pesan.show', compact('pesan', 'kategoris'));
    }

    public function jawab(Request $request, Pesan $pesan): RedirectResponse
    {
        $data = $request->validate([
            'jawaban' => 'required|string|max:5000',
            'publish' => 'nullable|boolean',
            'kategori' => 'nullable|string|max:50',
            'judul' => 'nullable|string|max:200',
            'excerpt' => 'nullable|string|max:500',
            'gambar' => 'nullable|image|max:2048',
        ]);

        $pesan->update([
            'jawaban' => $data['jawaban'],
            'dijawab_at' => now(),
            'is_dibaca' => true,
        ]);

        // Opsional: terbitkan Tanya-Jawab sebagai Berita/Artikel (seperti form berita).
        if ($request->boolean('publish')) {
            $kategori = in_array($data['kategori'] ?? '', ['berita', 'artikel', 'dawuh', 'bahtsul', 'pengumuman', 'acara', 'ppdb', 'prestasi', 'maklumat'])
                ? $data['kategori'] : 'artikel';
            $judul = trim((string) ($data['judul'] ?? ''));
            if ($judul === '') {
                $judul = trim((string) preg_replace('/^\[Konsultasi\]\s*/i', '', (string) ($pesan->subjek ?? 'Tanya Jawab')));
            }
            if ($judul === '') {
                $judul = 'Tanya Jawab: '.\Str::limit($pesan->pesan, 50);
            }
            $excerpt = trim((string) ($data['excerpt'] ?? ''));
            if ($excerpt === '') {
                $excerpt = \Str::limit($pesan->pesan, 160);
            }
            $konten = 'Pertanyaan dari '.$pesan->nama.":\n\n".$pesan->pesan."\n\n---\n\nJawaban:\n\n".$data['jawaban'];
            $gambarPath = null;
            if ($request->hasFile('gambar')) {
                $gambarPath = $request->file('gambar')->store('berita', 'public');
            }
            if ($pesan->berita_id && ($lama = \App\Models\Berita::find($pesan->berita_id))) {
                $upd = [
                    'judul' => $judul,
                    'kategori' => $kategori,
                    'excerpt' => $excerpt,
                    'konten' => $konten,
                    'is_published' => true,
                    'published_at' => $lama->published_at ?? now(),
                ];
                if ($gambarPath) {
                    $lama->deleteStoredFile($lama->gambar);
                    $upd['gambar'] = $gambarPath;
                }
                $lama->update($upd);
            } else {
                $berita = \App\Models\Berita::create([
                    'judul' => $judul,
                    'slug' => \Str::slug($judul).'-tanya-'.$pesan->id.'-'.time(),
                    'kategori' => $kategori,
                    'excerpt' => $excerpt,
                    'konten' => $konten,
                    'gambar' => $gambarPath,
                    'penulis' => auth()->user()->name ?? 'Admin',
                    'is_published' => true,
                    'published_at' => now(),
                ]);
                $pesan->update(['berita_id' => $berita->id]);
            }

            return back()->with('sukses', 'Jawaban disimpan & diterbitkan sebagai '.ucfirst($kategori).'.');
        }

        return back()->with('sukses', 'Jawaban disimpan (tidak dipublish).');
    }

    public function destroy(Pesan $pesan): RedirectResponse
    {
        $pesan->delete();
        return redirect()->route('admin.pesan.index')->with('sukses', 'Pesan dihapus.');
    }

    public function tandaiDibaca(Pesan $pesan): RedirectResponse
    {
        $pesan->update(['is_dibaca' => true]);
        return back()->with('sukses', 'Ditandai sudah dibaca.');
    }
}
