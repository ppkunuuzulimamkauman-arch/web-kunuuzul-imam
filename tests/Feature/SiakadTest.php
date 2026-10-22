<?php

namespace Tests\Feature;

use App\Models\AkademikTahunAjaran;
use App\Models\Alumni;
use App\Models\Pendaftaran;
use App\Models\Santri;
use App\Models\SuratPermohonan;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SiakadTest extends TestCase
{
    use DatabaseTransactions;

    public function test_siakad_pages_render_for_superadmin(): void
    {
        $this->withoutExceptionHandling();

        $admin = $this->adminUser();

        $this->actingAs($admin)->get(route('admin.siakad.dashboard'))->assertOk();
        $this->actingAs($admin)->get(route('admin.siakad.santri.index'))->assertOk();
        $this->actingAs($admin)->get(route('admin.siakad.santri.create'))->assertOk();
        $this->actingAs($admin)->get(route('admin.siakad.tahun-ajaran.index'))->assertOk();
        $this->actingAs($admin)->get(route('admin.siakad.tahun-ajaran.create'))->assertOk();
        $this->actingAs($admin)->get(route('admin.siakad.kelas.index'))->assertOk();
        $this->actingAs($admin)->get(route('admin.siakad.kelas.create'))->assertOk();
        $this->actingAs($admin)->get(route('admin.activity-logs.index'))->assertOk();
    }

    public function test_public_siakad_portal_renders(): void
    {
        $this->get('http://siakad.'.config('pondok.domain'))
            ->assertOk()
            ->assertSee('Portal SIAKAD');
    }

    public function test_admin_can_create_santri_and_activity_is_logged(): void
    {
        $admin = $this->adminUser();

        $this->actingAs($admin)
            ->post(route('admin.siakad.santri.store'), [
                'nama' => 'Ahmad Fauzi',
                'nik' => '3511010101010001',
                'nis' => 'SIAKAD-001',
                'jenis_kelamin' => 'L',
                'tempat_lahir' => 'Bondowoso',
                'tanggal_lahir' => '2008-01-15',
                'status' => 'aktif',
                'tanggal_masuk' => '2026-07-01',
            ])
            ->assertRedirect(route('admin.siakad.santri.index'));

        $santri = Santri::where('nis', 'SIAKAD-001')->firstOrFail();

        $this->assertSame('Ahmad Fauzi', $santri->nama);
        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $admin->id,
            'module' => 'siakad',
            'action' => 'tambah-santri',
            'subject_type' => Santri::class,
            'subject_id' => $santri->id,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.activity-logs.index'))
            ->assertOk()
            ->assertSee('tambah-santri');
    }

    public function test_tahun_ajaran_and_kelas_can_be_created(): void
    {
        $admin = $this->adminUser();

        $this->actingAs($admin)
            ->post(route('admin.siakad.tahun-ajaran.store'), [
                'nama' => '2026/2027',
                'mulai' => '2026-07-01',
                'selesai' => '2027-06-30',
                'is_aktif' => '1',
            ])
            ->assertRedirect(route('admin.siakad.tahun-ajaran.index'));

        $tahun = AkademikTahunAjaran::where('nama', '2026/2027')->firstOrFail();
        $this->assertTrue($tahun->is_aktif);

        $this->actingAs($admin)
            ->post(route('admin.siakad.kelas.store'), [
                'tahun_ajaran_id' => $tahun->id,
                'nama' => '7A',
                'jenjang' => 'MTs',
                'tingkat' => 7,
                'wali_kelas' => 'Ust. Contoh',
                'is_aktif' => '1',
            ])
            ->assertRedirect(route('admin.siakad.kelas.index'));

        $this->assertDatabaseHas('akademik_kelas', [
            'tahun_ajaran_id' => $tahun->id,
            'nama' => '7A',
            'jenjang' => 'MTs',
            'tingkat' => 7,
        ]);
    }

    public function test_psb_accepted_creates_and_links_santri(): void
    {
        $admin = $this->adminUser();

        $nik = '3511010101010001';
        $pendaftaran = Pendaftaran::create([
            'nama_lengkap' => 'Pendaftar Diterima',
            'nik' => $nik,
            'jenis_kelamin' => 'L',
            'alamat' => 'Kauman',
            'nama_wali' => 'Wali Pendaftar',
            'hp_wali' => '081234567890',
            'status' => 'menunggu',
        ]);

        $this->actingAs($admin)
            ->patch(route('admin.pendaftaran.status', $pendaftaran), ['status' => 'diterima'])
            ->assertRedirect();

        $santri = Santri::where('nik', $nik)->firstOrFail();

        $this->assertSame('Pendaftar Diterima', $santri->nama);
        $this->assertSame($santri->id, $pendaftaran->fresh()->santri_id);
        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $admin->id,
            'module' => 'psb',
            'action' => 'ubah-status',
            'subject_id' => $pendaftaran->id,
        ]);
    }

    public function test_alumni_registration_links_santri_by_nik(): void
    {
        config(['services.turnstile.site_key' => null, 'services.turnstile.secret' => null]);

        $nik = '3511010101010001';
        $santri = Santri::create([
            'nik' => $nik,
            'nama' => 'Santri Alumni',
            'jenis_kelamin' => 'L',
            'status' => 'lulus',
        ]);

        $this->withSession(['alumni_nik' => $nik])
            ->post(route('alumni.register.store'), [
                'nik' => $nik,
                'nama' => 'Calon Alumni',
                'jenis_kelamin' => 'L',
                'status_hidup' => 'hidup',
                'tempat_lahir' => 'Bondowoso',
                'tanggal_lahir' => '2000-01-01',
                'no_hp' => '081234567890',
                'provinsi' => 'Jawa Timur',
                'kabupaten' => 'Bondowoso',
                'kecamatan' => 'Binakal',
                'desa' => 'Desa Contoh',
                'dusun' => 'Dusun Contoh',
                'rt' => '001',
                'rw' => '002',
            ])
            ->assertRedirect(route('alumni.register.success'));

        $alumni = Alumni::where('nama', 'Calon Alumni')->firstOrFail();

        $this->assertSame($santri->id, $alumni->santri_id);
    }

    public function test_surat_request_links_santri_by_nik(): void
    {
        config(['services.turnstile.site_key' => null, 'services.turnstile.secret' => null]);

        $nik = '3511010101010001';
        $santri = Santri::create([
            'nik' => $nik,
            'nama' => 'Santri Pemohon',
            'jenis_kelamin' => 'L',
            'status' => 'aktif',
        ]);

        $this->post(route('surat.store'), [
            'jenis' => 'keterangan_aktif',
            'nama' => 'Santri Pemohon',
            'nik' => $nik,
            'no_hp' => '081234567890',
            'keperluan' => 'Keperluan pengujian integrasi SIAKAD.',
            'setuju' => '1',
        ])->assertRedirect();

        $surat = SuratPermohonan::latest('id')->firstOrFail();

        $this->assertSame($santri->id, $surat->santri_id);
    }

    public function test_legacy_link_command_links_existing_records(): void
    {
        $nik = '3511010101010001';

        $pendaftaran = Pendaftaran::create([
            'nama_lengkap' => 'Pendaftar Lama',
            'nik' => $nik,
            'jenis_kelamin' => 'L',
            'alamat' => 'Kauman',
            'nama_wali' => 'Wali Pendaftar',
            'hp_wali' => '081234567890',
            'status' => 'diterima',
        ]);

        $alumni = Alumni::create([
            'nama' => 'Alumni Lama',
            'nik' => $nik,
        ]);

        $surat = SuratPermohonan::create([
            'kode' => 'SRT-TEST-'.uniqid(),
            'jenis' => 'keterangan_aktif',
            'nama' => 'Pemohon Lama',
            'nik' => $nik,
            'no_hp' => '081234567890',
            'keperluan' => 'Pengujian backfill data lama.',
            'status' => 'menunggu',
        ]);

        $this->artisan('siakad:link-legacy')->assertSuccessful();

        $santri = Santri::where('nik', $nik)->firstOrFail();

        $this->assertSame($santri->id, $pendaftaran->fresh()->santri_id);
        $this->assertSame($santri->id, $alumni->fresh()->santri_id);
        $this->assertSame($santri->id, $surat->fresh()->santri_id);
    }

    private function adminUser(): User
    {
        return User::create([
            'name' => 'Admin SIAKAD',
            'email' => 'admin-siakad-'.uniqid().'@example.test',
            'password' => 'password123',
            'role' => 'superadmin',
            'is_active' => true,
        ]);
    }
}
