<?php

namespace Tests\Unit;

use App\Services\Totp;
use PHPUnit\Framework\TestCase;

class TotpTest extends TestCase
{
    /** Vektor uji resmi RFC 6238 (SHA1, 6 digit). */
    public function test_menghasilkan_kode_sesuai_rfc_6238(): void
    {
        $secret = Totp::base32Encode('12345678901234567890');

        $this->assertSame('287082', Totp::code($secret, 59));
        $this->assertSame('081804', Totp::code($secret, 1111111109));
        $this->assertSame('005924', Totp::code($secret, 1234567890));
        $this->assertSame('279037', Totp::code($secret, 2000000000));
    }

    public function test_verifikasi_menerima_kode_valid_dan_menolak_yang_salah(): void
    {
        $secret = Totp::generateSecret();
        $code = Totp::code($secret);

        $this->assertTrue(Totp::verify($secret, $code));
        $this->assertFalse(Totp::verify($secret, str_pad((string) (((int) $code + 1) % 1000000), 6, '0', STR_PAD_LEFT)));
        $this->assertFalse(Totp::verify($secret, 'abcdef'));
    }
}
