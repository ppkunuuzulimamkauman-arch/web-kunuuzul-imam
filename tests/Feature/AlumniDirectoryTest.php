<?php

namespace Tests\Feature;

use App\Models\Alumni;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class AlumniDirectoryTest extends TestCase
{
    use DatabaseTransactions;

    public function test_direktori_publik_hanya_menampilkan_alumni_yang_tampil(): void
    {
        Alumni::create(['nama' => 'Alumni Tampil', 'angkatan' => 2010, 'is_tampil' => true]);
        Alumni::create(['nama' => 'Alumni Sembunyi', 'angkatan' => 2011, 'is_tampil' => false]);
        Alumni::create([
            'nama' => 'Alumni Menunggu',
            'angkatan' => 2012,
            'status' => Alumni::STATUS_PENDING,
            'is_tampil' => true,
        ]);

        $this->get(route('alumni.home'))
            ->assertOk()
            ->assertSee('Alumni Tampil')
            ->assertDontSee('Alumni Sembunyi')
            ->assertDontSee('Alumni Menunggu');
    }

    public function test_detail_alumni_yang_disembunyikan_menghasilkan_404(): void
    {
        $sembunyi = Alumni::create(['nama' => 'Alumni Sembunyi', 'is_tampil' => false]);
        $menunggu = Alumni::create([
            'nama' => 'Alumni Menunggu',
            'status' => Alumni::STATUS_PENDING,
            'is_tampil' => true,
        ]);

        $this->get(route('alumni.show', $sembunyi))->assertNotFound();
        $this->get(route('alumni.show', $menunggu))->assertNotFound();
    }

    public function test_halaman_form_dan_sukses_pendaftaran_bisa_diakses(): void
    {
        $this->get(route('alumni.register'))
            ->assertOk()
            ->assertSee('Pendaftaran Alumni');

        $this->get(route('alumni.register.success'))
            ->assertOk()
            ->assertSee('Pendaftaran terkirim');
    }

    public function test_pendaftaran_publik_masuk_sebagai_menunggu_dan_belum_tampil(): void
    {
        config(['services.turnstile.site_key' => null, 'services.turnstile.secret' => null]);

        $this->post(route('alumni.register.store'), [
            'nama' => 'Calon Alumni',
            'angkatan' => 2020,
            'tahun_lulus' => 2024,
            'lembaga' => 'MA',
            'domisili' => 'Jombang',
            'pekerjaan' => 'Mahasiswa',
            'no_hp' => '081234567890',
            'email' => 'calon-alumni@example.test',
            'tentang' => 'Siap bersilaturahmi.',
        ])->assertRedirect(route('alumni.register.success'));

        $alumni = Alumni::where('nama', 'Calon Alumni')->firstOrFail();
        $this->assertSame(Alumni::STATUS_PENDING, $alumni->status);
        $this->assertFalse((bool) $alumni->is_tampil);

        $this->get(route('alumni.home'))
            ->assertOk()
            ->assertDontSee('Calon Alumni');
    }

    public function test_honeypot_pendaftaran_tidak_membuat_data(): void
    {
        $this->post(route('alumni.register.store'), [
            'website' => 'http://spam.test',
            'nama' => 'Bot Alumni',
            'no_hp' => '081234567890',
        ])->assertRedirect(route('alumni.register.success'));

        $this->assertDatabaseMissing('alumnis', ['nama' => 'Bot Alumni']);
    }

    public function test_admin_menyetujui_pendaftaran_alumni(): void
    {
        $alumni = Alumni::create([
            'nama' => 'Alumni Menunggu',
            'status' => Alumni::STATUS_PENDING,
            'is_tampil' => false,
        ]);

        $this->actingAs($this->adminUser('approve'))
            ->post(route('admin.alumni.approve', $alumni))
            ->assertRedirect();

        $alumni->refresh();
        $this->assertSame(Alumni::STATUS_APPROVED, $alumni->status);
        $this->assertTrue((bool) $alumni->is_tampil);

        $this->get(route('alumni.home'))
            ->assertOk()
            ->assertSee('Alumni Menunggu');
    }

    public function test_admin_menolak_pendaftaran_alumni(): void
    {
        $alumni = Alumni::create([
            'nama' => 'Alumni Ditolak',
            'status' => Alumni::STATUS_PENDING,
            'is_tampil' => true,
        ]);

        $this->actingAs($this->adminUser('reject'))
            ->post(route('admin.alumni.reject', $alumni), [
                'catatan_admin' => 'Data belum lengkap.',
            ])
            ->assertRedirect();

        $alumni->refresh();
        $this->assertSame(Alumni::STATUS_REJECTED, $alumni->status);
        $this->assertFalse((bool) $alumni->is_tampil);
        $this->assertSame('Data belum lengkap.', $alumni->catatan_admin);

        $this->get(route('alumni.home'))
            ->assertOk()
            ->assertDontSee('Alumni Ditolak');
    }

    public function test_admin_bisa_menambah_mengubah_dan_menghapus_alumni(): void
    {
        $utama = $this->adminUser('crud');

        $this->actingAs($utama)
            ->get(route('admin.alumni.index'))
            ->assertOk();

        $this->actingAs($utama)
            ->post(route('admin.alumni.store'), [
                'nama' => 'Alumni Baru',
                'angkatan' => 2015,
                'tahun_lulus' => 2019,
                'lembaga' => 'MA',
                'domisili' => 'Jombang',
                'pekerjaan' => 'Guru',
                'no_hp' => '081234567890',
                'email' => 'alumni-baru@example.test',
                'tentang' => 'Catatan alumni.',
                'status' => Alumni::STATUS_APPROVED,
                'is_tampil' => '1',
            ])
            ->assertRedirect(route('admin.alumni.index'));

        $alumni = Alumni::where('nama', 'Alumni Baru')->firstOrFail();
        $this->assertTrue((bool) $alumni->is_tampil);
        $this->assertSame(Alumni::STATUS_APPROVED, $alumni->status);

        $this->actingAs($utama)
            ->put(route('admin.alumni.update', $alumni), [
                'nama' => 'Alumni Diubah',
                'angkatan' => 2015,
                'status' => Alumni::STATUS_APPROVED,
                'is_tampil' => '1',
            ])
            ->assertRedirect(route('admin.alumni.index'));

        $this->assertDatabaseHas('alumnis', [
            'id' => $alumni->id,
            'nama' => 'Alumni Diubah',
        ]);

        $this->actingAs($utama)
            ->delete(route('admin.alumni.destroy', $alumni))
            ->assertRedirect();

        $this->assertDatabaseMissing('alumnis', ['id' => $alumni->id]);
    }

    public function test_admin_bisa_export_csv_alumni(): void
    {
        $admin = $this->adminUser('export');
        Alumni::create([
            'nama' => 'Alumni Export',
            'angkatan' => 2018,
            'domisili' => 'Surabaya',
            'status' => Alumni::STATUS_APPROVED,
            'is_tampil' => true,
        ]);

        $res = $this->actingAs($admin)->get(route('admin.alumni.export'));

        $res->assertOk();
        $res->assertHeader('content-type', 'text/csv; charset=UTF-8');
        $this->assertStringContainsString('Alumni Export', $res->streamedContent());
        $this->assertStringContainsString('Disetujui', $res->streamedContent());
    }

    public function test_admin_bisa_mengunduh_template_import(): void
    {
        $res = $this->actingAs($this->adminUser('template'))
            ->get(route('admin.alumni.import.template'));

        $res->assertOk();
        $res->assertHeader('content-type', 'text/csv; charset=UTF-8');
        $this->assertStringContainsString('Nama,Angkatan', $res->streamedContent());
    }

    public function test_admin_bisa_import_csv_alumni(): void
    {
        $csv = $this->alumniCsv([
            ['Alumni Import', '2010', '2014', 'MA', 'Jombang', 'Guru', '081234567890', 'import@example.test', 'Disetujui', 'ya', 'Test import', ''],
            ['Alumni Pending', '2011', '', '', '', '', '', '', 'Menunggu', '', '', ''],
        ]);

        $this->actingAs($this->adminUser('import'))
            ->post(route('admin.alumni.import.store'), [
                'file' => UploadedFile::fake()->createWithContent('alumni.csv', $csv),
            ])
            ->assertRedirect(route('admin.alumni.import'))
            ->assertSessionHas('sukses');

        $imported = Alumni::where('nama', 'Alumni Import')->firstOrFail();
        $this->assertSame(Alumni::STATUS_APPROVED, $imported->status);
        $this->assertTrue((bool) $imported->is_tampil);
        $this->assertSame(2010, $imported->angkatan);
        $this->assertSame(2014, $imported->tahun_lulus);
        $this->assertSame('Test import', $imported->tentang);

        $pending = Alumni::where('nama', 'Alumni Pending')->firstOrFail();
        $this->assertSame(Alumni::STATUS_PENDING, $pending->status);
        $this->assertFalse((bool) $pending->is_tampil);
    }

    public function test_import_csv_melewati_duplikat_dan_baris_invalid(): void
    {
        Alumni::create([
            'nama' => 'Alumni Duplikat',
            'angkatan' => 2012,
            'status' => Alumni::STATUS_APPROVED,
            'is_tampil' => true,
        ]);

        $csv = $this->alumniCsv([
            ['Alumni Duplikat', '2012', '', '', '', '', '', '', 'Disetujui', '', '', ''],
            ['', '2020', '', '', '', '', '', '', 'Disetujui', '', '', ''],
            ['Alumni Baru Import', '2021', '', '', '', '', '', '', 'Disetujui', '', '', ''],
        ]);

        $this->actingAs($this->adminUser('import-duplikat'))
            ->post(route('admin.alumni.import.store'), [
                'file' => UploadedFile::fake()->createWithContent('alumni.csv', $csv),
            ])
            ->assertRedirect(route('admin.alumni.import'))
            ->assertSessionHas('import_errors');

        $this->assertSame(1, Alumni::where('nama', 'Alumni Duplikat')->count());
        $this->assertDatabaseHas('alumnis', ['nama' => 'Alumni Baru Import']);
    }

    public function test_halaman_statistik_publik_menampilkan_data_yang_tampil(): void
    {
        Alumni::create([
            'nama' => 'Stats Satu',
            'angkatan' => 2010,
            'lembaga' => 'MA',
            'domisili' => 'Jombang',
            'is_tampil' => true,
        ]);
        Alumni::create([
            'nama' => 'Stats Dua',
            'angkatan' => 2010,
            'lembaga' => 'MA',
            'domisili' => 'Surabaya',
            'is_tampil' => true,
        ]);
        Alumni::create([
            'nama' => 'Stats Tiga',
            'angkatan' => 2011,
            'lembaga' => 'MTs',
            'domisili' => 'Jombang',
            'status' => Alumni::STATUS_PENDING,
            'is_tampil' => true,
        ]);

        $this->get(route('alumni.stats'))
            ->assertOk()
            ->assertSee('Statistik Alumni')
            ->assertSee('2010')
            ->assertSee('2 alumni')
            ->assertSee('MA')
            ->assertSee('Surabaya')
            ->assertDontSee('MTs');
    }

    /** @param array<int, array<int, string>> $rows */
    private function alumniCsv(array $rows): string
    {
        $header = ['Nama', 'Angkatan', 'Tahun Lulus', 'Lembaga', 'Domisili', 'Pekerjaan', 'No. HP', 'Email', 'Status', 'Tampil', 'Tentang', 'Catatan Admin'];

        return implode("\n", array_merge(
            [implode(',', $header)],
            array_map(fn (array $row) => implode(',', $row), $rows)
        ));
    }

    private function adminUser(string $suffix): User
    {
        return User::create([
            'name' => 'Admin '.$suffix,
            'email' => "admin-{$suffix}@example.test",
            'password' => 'password123',
            'role' => 'superadmin',
            'is_active' => true,
        ]);
    }
}
