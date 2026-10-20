<?php

namespace Tests\Unit;

use App\Support\WilayahPk;
use PHPUnit\Framework\TestCase;

class WilayahPkTest extends TestCase
{
    public function test_memetakan_kecamatan_bondowoso_ke_wilayah_pk(): void
    {
        $this->assertSame('PK 1', WilayahPk::for('Bondowoso', 'Binakal'));
        $this->assertSame('PK 1', WilayahPk::for('Kabupaten Bondowoso', 'Kec. Curahdami'));
        $this->assertSame('PK 3', WilayahPk::for('Bondowoso', 'Tenggarang'));
        $this->assertSame('PK 3', WilayahPk::for('Bondowoso', 'Taman Krocok'));
        $this->assertSame('PK 4', WilayahPk::for('Bondowoso', 'Pujer'));
        $this->assertSame('PK 5', WilayahPk::for('Bondowoso', 'Maesan'));
        $this->assertSame('PK 6', WilayahPk::for('Bondowoso', 'Wringin'));
        $this->assertSame('PK 7', WilayahPk::for('Bondowoso', 'Tamanan'));
        $this->assertSame('PK 7', WilayahPk::for('Bondowoso', 'Jambesari DS'));
        $this->assertSame('PK 7', WilayahPk::for('Bondowoso', 'Jambesari Darus Sholah'));
    }

    public function test_semua_kecamatan_jember_masuk_pk_2(): void
    {
        $this->assertSame('PK 2', WilayahPk::for('Jember', 'Kaliwates'));
        $this->assertSame('PK 2', WilayahPk::for('Kabupaten Jember', 'Sukorambi'));
    }

    public function test_wilayah_di_luar_pemetaan_tidak_dipaksa_masuk_pk(): void
    {
        $this->assertNull(WilayahPk::for('Bondowoso', 'Bondowoso'));
        $this->assertNull(WilayahPk::for('Surabaya', 'Gubeng'));
        $this->assertNull(WilayahPk::for(null, null));
    }

    public function test_label_wilayah_pk(): void
    {
        $this->assertSame('Wilayah PK 1', WilayahPk::label('PK 1'));
        $this->assertSame('Belum ditentukan', WilayahPk::label(null));
    }
}
