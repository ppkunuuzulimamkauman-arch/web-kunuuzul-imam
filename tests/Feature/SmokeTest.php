<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class SmokeTest extends TestCase
{
    #[Test]
    public function halaman_publik_utama_bisa_diakses(): void
    {
        foreach (['/', '/profil', '/berita', '/artikel', '/galeri', '/ppdb', '/kontak', '/konsultasi', '/lembaga', '/organisasi'] as $uri) {
            $this->get($uri)->assertOk();
        }
    }

    #[Test]
    public function sitemap_valid(): void
    {
        $res = $this->get('/sitemap.xml');
        $res->assertOk();
        $this->assertStringContainsString('text/xml', $res->headers->get('Content-Type'));
        $this->assertStringContainsString('<urlset', $res->getContent());
    }

    #[Test]
    public function halaman_tidak_ada_memakai_404_kustom(): void
    {
        $res = $this->get('/ini-pasti-tidak-ada-123');
        $res->assertNotFound();
        $this->assertStringContainsString('Halaman Tidak Ditemukan', $res->getContent());
    }

    #[Test]
    public function security_headers_terpasang(): void
    {
        $res = $this->get('/');
        $this->assertEquals('nosniff', $res->headers->get('X-Content-Type-Options'));
        $this->assertEquals('SAMEORIGIN', $res->headers->get('X-Frame-Options'));
    }

    #[Test]
    public function panel_admin_butuh_login(): void
    {
        $this->get('/admin')->assertRedirect();
        $this->get('/admin/login')->assertOk();
    }

    #[Test]
    public function meta_seo_ada_di_beranda(): void
    {
        $body = $this->get('/')->getContent();
        $this->assertStringContainsString('og:title', $body);
        $this->assertStringContainsString('og:image', $body);
        $this->assertStringContainsString('rel="canonical"', $body);
    }
}
