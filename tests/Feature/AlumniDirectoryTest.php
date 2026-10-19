<?php

namespace Tests\Feature;

use App\Models\Alumni;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AlumniDirectoryTest extends TestCase
{
    use DatabaseTransactions;

    public function test_direktori_publik_hanya_menampilkan_alumni_yang_tampil(): void
    {
        Alumni::create(['nama' => 'Alumni Tampil', 'angkatan' => 2010, 'is_tampil' => true]);
        Alumni::create(['nama' => 'Alumni Sembunyi', 'angkatan' => 2011, 'is_tampil' => false]);

        $this->get(route('alumni.home'))
            ->assertOk()
            ->assertSee('Alumni Tampil')
            ->assertDontSee('Alumni Sembunyi');
    }

    public function test_detail_alumni_yang_disembunyikan_menghasilkan_404(): void
    {
        $sembunyi = Alumni::create(['nama' => 'Alumni Sembunyi', 'is_tampil' => false]);

        $this->get(route('alumni.show', $sembunyi))->assertNotFound();
    }

    public function test_admin_bisa_menambah_mengubah_dan_menghapus_alumni(): void
    {
        $utama = User::create([
            'name' => 'Utama Tes',
            'email' => 'utama-alumni@example.test',
            'password' => 'password123',
            'role' => 'superadmin',
            'is_active' => true,
        ]);

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
                'is_tampil' => '1',
            ])
            ->assertRedirect(route('admin.alumni.index'));

        $alumni = Alumni::where('nama', 'Alumni Baru')->firstOrFail();
        $this->assertTrue((bool) $alumni->is_tampil);

        $this->actingAs($utama)
            ->put(route('admin.alumni.update', $alumni), [
                'nama' => 'Alumni Diubah',
                'angkatan' => 2015,
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
}
