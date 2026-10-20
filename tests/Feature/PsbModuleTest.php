<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class PsbModuleTest extends TestCase
{
    use DatabaseTransactions;

    private const KEYS = [
        'psb_tahun_ajaran',
        'psb_status',
        'psb_gelombang',
        'psb_syarat',
        'psb_biaya_info',
        'psb_narahubung',
    ];

    public function test_halaman_publik_psb_tampil(): void
    {
        $this->get(route('psb.home'))
            ->assertOk()
            ->assertSee('Penerimaan Santri Baru')
            ->assertSee('Daftar Sekarang')
            ->assertSee('Cek Status Pendaftaran')
            ->assertSee('Syarat & berkas')
            ->assertSee('Pertanyaan yang sering diajukan');
    }

    public function test_admin_bisa_melihat_dan_menyimpan_informasi_psb(): void
    {
        $admin = $this->adminUser();

        $this->actingAs($admin)
            ->get(route('admin.psb.index'))
            ->assertOk()
            ->assertSee('Informasi Halaman PSB')
            ->assertSee('Pendaftar Terbaru');

        $this->actingAs($admin)
            ->post(route('admin.psb.update'), [
                'psb_tahun_ajaran' => '2027/2028',
                'psb_status' => 'Segera Dibuka',
                'psb_gelombang' => 'Gelombang 1|1 Juli 2027|Kuota terbatas',
                'psb_syarat' => "Kartu Keluarga\nIjazah",
                'psb_biaya_info' => 'Info biaya uji coba.',
                'psb_narahubung' => '081234567890',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('settings', ['key' => 'psb_tahun_ajaran', 'value' => '2027/2028']);
        $this->assertDatabaseHas('settings', ['key' => 'psb_status', 'value' => 'Segera Dibuka']);

        $this->get(route('psb.home'))
            ->assertOk()
            ->assertSee('2027/2028')
            ->assertSee('SEGERA DIBUKA')
            ->assertSee('Gelombang 1');

        foreach (self::KEYS as $key) {
            Cache::forget('setting:'.$key);
        }
    }

    public function test_tamu_tidak_bisa_membuka_admin_psb(): void
    {
        $this->get(route('admin.psb.index'))->assertRedirect(route('login'));
    }

    public function test_pintasan_daftar_mengarah_ke_form_ppdb(): void
    {
        $this->get(route('psb.daftar'))->assertRedirect(rtrim((string) config('app.url'), '/').'/ppdb');
        $this->get(route('psb.cek'))->assertRedirect(rtrim((string) config('app.url'), '/').'/ppdb/cek');
    }

    private function adminUser(): User
    {
        return User::create([
            'name' => 'Admin PSB',
            'email' => 'admin-psb-'.uniqid().'@example.test',
            'password' => 'password123',
            'role' => 'superadmin',
            'is_active' => true,
        ]);
    }
}
