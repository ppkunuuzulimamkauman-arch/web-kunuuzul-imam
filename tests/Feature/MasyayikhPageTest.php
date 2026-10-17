<?php

namespace Tests\Feature;

use App\Models\Pengasuh;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class MasyayikhPageTest extends TestCase
{
    use DatabaseTransactions;

    public function test_halaman_masyayikh_dan_detail_biografi(): void
    {
        $p = Pengasuh::create([
            'nama' => 'KH. Uji Coba',
            'jabatan' => 'Pendiri',
            'bio' => '(1842-1918)',
            'biografi' => 'Beliau adalah pendiri pondok yang gigih.',
            'is_aktif' => true,
            'urutan' => 1,
        ]);

        $this->get(route('profil.struktur'))
            ->assertOk()
            ->assertSee('KH. Uji Coba');

        $this->get(route('profil.masyayikh', $p->id))
            ->assertOk()
            ->assertSee('Beliau adalah pendiri pondok yang gigih.');

        $this->get(route('profil.masyayikh', 999999))->assertNotFound();
    }
}
