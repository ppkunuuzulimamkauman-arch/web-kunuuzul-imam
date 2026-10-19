<?php

namespace App\Services;

/**
 * TOTP (RFC 6238) tanpa dependency eksternal.
 * Kompatibel dengan Google Authenticator, Authy, Microsoft Authenticator, dll.
 */
class Totp
{
    private const ALPHABET = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';

    /** Buat secret acak 160-bit dalam format Base32. */
    public static function generateSecret(int $bytes = 20): string
    {
        return self::base32Encode(random_bytes($bytes));
    }

    /** Hitung kode 6 digit untuk waktu tertentu (default sekarang). */
    public static function code(string $secret, ?int $time = null, int $digits = 6, int $period = 30): string
    {
        $time = $time ?? time();
        $counter = intdiv($time, $period);
        $key = self::base32Decode($secret);
        $binary = pack('N*', 0).pack('N*', $counter);
        $hash = hash_hmac('sha1', $binary, $key, true);
        $offset = ord($hash[19]) & 0x0F;
        $value = ((ord($hash[$offset]) & 0x7F) << 24)
            | ((ord($hash[$offset + 1]) & 0xFF) << 16)
            | ((ord($hash[$offset + 2]) & 0xFF) << 8)
            | (ord($hash[$offset + 3]) & 0xFF);

        return str_pad((string) ($value % (10 ** $digits)), $digits, '0', STR_PAD_LEFT);
    }

    /**
     * Verifikasi kode dengan toleransi +/- $window langkah (default 1 = 30 detik
     * ke belakang & ke depan) untuk mengakomodasi jam perangkat yang meleset.
     */
    public static function verify(string $secret, string $code, int $window = 1, int $digits = 6, int $period = 30): bool
    {
        $code = preg_replace('/\s+/', '', $code) ?? '';
        if (! preg_match('/^\d{'.$digits.'}$/', $code)) {
            return false;
        }

        $now = time();
        for ($i = -$window; $i <= $window; $i++) {
            if (hash_equals(self::code($secret, $now + ($i * $period), $digits, $period), $code)) {
                return true;
            }
        }

        return false;
    }

    /** URI otpauth:// untuk dirender sebagai QR code. */
    public static function provisioningUri(string $secret, string $label, string $issuer): string
    {
        $params = http_build_query([
            'secret' => $secret,
            'issuer' => $issuer,
            'algorithm' => 'SHA1',
            'digits' => 6,
            'period' => 30,
        ]);

        return 'otpauth://totp/'.rawurlencode($label).'?'.$params;
    }

    public static function base32Encode(string $data): string
    {
        $bits = '';
        for ($i = 0, $len = strlen($data); $i < $len; $i++) {
            $bits .= str_pad(decbin(ord($data[$i])), 8, '0', STR_PAD_LEFT);
        }

        $out = '';
        foreach (str_split($bits, 5) as $chunk) {
            $out .= self::ALPHABET[bindec(str_pad($chunk, 5, '0', STR_PAD_RIGHT))];
        }

        return $out;
    }

    public static function base32Decode(string $encoded): string
    {
        $encoded = strtoupper(preg_replace('/[^A-Z2-7]/i', '', $encoded) ?? '');
        $bits = '';
        for ($i = 0, $len = strlen($encoded); $i < $len; $i++) {
            $pos = strpos(self::ALPHABET, $encoded[$i]);
            if ($pos === false) {
                continue;
            }
            $bits .= str_pad(decbin($pos), 5, '0', STR_PAD_LEFT);
        }

        $out = '';
        foreach (str_split($bits, 8) as $byte) {
            if (strlen($byte) === 8) {
                $out .= chr(bindec($byte));
            }
        }

        return $out;
    }
}
