<?php

namespace Tests\Feature;

use App\Models\SuratPermohonan;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SuratModuleTest extends TestCase
{
    use DatabaseTransactions;

    public function test_halaman_publik_surat_bisa_diakses(): void
    {
        $this->get(route('surat.home'))
            ->assertOk()
            ->assertSee('Layanan Surat Online')
            ->assertSee('Surat Keterangan Aktif Santri');

        $this->get(route('surat.create'))
            ->assertOk()
            ->assertSee('Ajukan Surat')
            ->assertSee('Data Pemohon');

        $this->get(route('surat.cek'))
            ->assertOk()
            ->assertSee('Cek Status Surat');
    }

    public function test_permohonan_baru_tersimpan_dengan_status_menunggu(): void
    {
        config(['services.turnstile.site_key' => null, 'services.turnstile.secret' => null]);

        $response = $this->post(route('surat.store'), $this->payload([
            'nama' => 'Pemohon Uji',
            'jenis' => 'keterangan_aktif',
        ]));

        $permohonan = SuratPermohonan::where('nama', 'Pemohon Uji')->firstOrFail();

        $response->assertRedirect(route('surat.sukses', ['kode' => $permohonan->kode]));

        $this->assertSame(SuratPermohonan::STATUS_MENUNGGU, $permohonan->status);
        $this->assertStringStartsWith('SRT-', $permohonan->kode);

        $this->get(route('surat.sukses', ['kode' => $permohonan->kode]))
            ->assertOk()
            ->assertSee($permohonan->kode)
            ->assertSee('Pemohon Uji');

        $this->get(route('surat.cek', ['kode' => $permohonan->kode]))
            ->assertOk()
            ->assertSee('Progres Permohonan')
            ->assertSee('Menunggu Verifikasi');
    }

    public function test_izin_pulang_mewajibkan_tanggal_dan_tujuan(): void
    {
        config(['services.turnstile.site_key' => null, 'services.turnstile.secret' => null]);

        $this->post(route('surat.store'), $this->payload(['jenis' => 'izin_pulang']))
            ->assertSessionHasErrors(['tanggal_mulai', 'tanggal_selesai', 'tujuan']);

        $this->assertDatabaseMissing('surat_permohonan', ['jenis' => 'izin_pulang']);
    }

    public function test_honeypot_tidak_membuat_permohonan(): void
    {
        $this->post(route('surat.store'), $this->payload([
            'website' => 'http://spam.test',
            'nama' => 'Bot Surat',
        ]))->assertRedirect(route('surat.home'));

        $this->assertDatabaseMissing('surat_permohonan', ['nama' => 'Bot Surat']);
    }

    public function test_kode_tidak_ditemukan_menampilkan_pesan(): void
    {
        $this->get(route('surat.cek', ['kode' => 'SRT-000000-XXXXXX']))
            ->assertOk()
            ->assertSee('Permohonan tidak ditemukan');
    }

    public function test_admin_bisa_melihat_dan_memproses_permohonan(): void
    {
        $admin = $this->adminUser();
        $permohonan = SuratPermohonan::create($this->payload([
            'nama' => 'Pemohon Admin',
            'jenis' => 'rekomendasi',
            'ditujukan' => 'Universitas Uji',
            'kode' => SuratPermohonan::generateKode(),
            'status' => SuratPermohonan::STATUS_MENUNGGU,
        ]));

        $this->actingAs($admin)
            ->get(route('admin.surat.index'))
            ->assertOk()
            ->assertSee('Pemohon Admin');

        $this->actingAs($admin)
            ->get(route('admin.surat.show', $permohonan))
            ->assertOk()
            ->assertSee($permohonan->kode)
            ->assertSee('Universitas Uji');

        $this->actingAs($admin)
            ->patch(route('admin.surat.update', $permohonan), [
                'status' => SuratPermohonan::STATUS_SELESAI,
                'nomor_surat' => '042/PPS-KIK/X/2026',
                'catatan_admin' => 'Surat sudah selesai.',
            ])
            ->assertRedirect();

        $permohonan->refresh();
        $this->assertSame(SuratPermohonan::STATUS_SELESAI, $permohonan->status);
        $this->assertSame('042/PPS-KIK/X/2026', $permohonan->nomor_surat);
        $this->assertNotNull($permohonan->selesai_at);

        $this->get(route('surat.cek', ['kode' => $permohonan->kode]))
            ->assertOk()
            ->assertSee('Selesai')
            ->assertSee('042/PPS-KIK/X/2026')
            ->assertSee('Surat sudah selesai.');
    }

    public function test_admin_bisa_export_dan_menghapus_permohonan(): void
    {
        $admin = $this->adminUser();
        $permohonan = SuratPermohonan::create($this->payload([
            'nama' => 'Pemohon Export',
            'jenis' => 'pengantar',
            'ditujukan' => 'Kantor Desa',
            'kode' => SuratPermohonan::generateKode(),
            'status' => SuratPermohonan::STATUS_MENUNGGU,
        ]));

        $response = $this->actingAs($admin)->get(route('admin.surat.export'));

        $response->assertOk();
        $response->assertHeader('content-type', 'text/csv; charset=UTF-8');
        $this->assertStringContainsString('Pemohon Export', $response->streamedContent());
        $this->assertStringContainsString($permohonan->kode, $response->streamedContent());

        $this->actingAs($admin)
            ->delete(route('admin.surat.destroy', $permohonan))
            ->assertRedirect(route('admin.surat.index'));

        $this->assertDatabaseMissing('surat_permohonan', ['id' => $permohonan->id]);
    }

    /** @param array<string, mixed> $overrides */
    private function payload(array $overrides = []): array
    {
        return array_merge([
            'jenis' => 'keterangan_aktif',
            'nama' => 'Pemohon Tes',
            'nik' => '1234567890123456',
            'no_hp' => '081234567890',
            'email' => 'pemohon@example.test',
            'lembaga' => 'MA',
            'alamat' => 'Kauman',
            'keperluan' => 'Keperluan pengujian modul surat.',
            'setuju' => '1',
        ], $overrides);
    }

    private function adminUser(): User
    {
        return User::create([
            'name' => 'Admin Surat',
            'email' => 'admin-surat-'.uniqid().'@example.test',
            'password' => 'password123',
            'role' => 'superadmin',
            'is_active' => true,
        ]);
    }
}
