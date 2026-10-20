<?php

namespace Tests\Feature;

use App\Models\Berita;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SearchTest extends TestCase
{
    use DatabaseTransactions;

    public function test_pencarian_menampilkan_berita_yang_cocok(): void
    {
        Berita::create([
            'judul' => 'Kajian Kitab Kuning Rutin',
            'slug' => 'kajian-kitab-kuning-rutin-uji',
            'kategori' => 'berita',
            'excerpt' => 'Kajian kitab kuning bersama masyayikh.',
            'konten' => 'Kegiatan kajian kitab kuning di pondok.',
            'is_published' => true,
            'published_at' => now(),
        ]);

        $this->get(route('search', ['q' => 'Kajian Kitab']))
            ->assertOk()
            ->assertSee('Kajian Kitab Kuning Rutin')
            ->assertSee('Hasil untuk');
    }

    public function test_pencarian_kosong_menampilkan_petunjuk(): void
    {
        $this->get(route('search'))
            ->assertOk()
            ->assertSee('Ketik kata kunci untuk mulai mencari');
    }
}
