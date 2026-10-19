<?php

namespace App\Support;

class Nik
{
    /** Bersihkan NIK dari spasi/tanda baca. */
    public static function normalize(?string $nik): ?string
    {
        $nik = preg_replace('/\D+/', '', (string) $nik);

        return $nik === '' ? null : $nik;
    }

    /**
     * Validasi dasar NIK Indonesia: 16 digit, kode wilayah, dan tanggal lahir.
     * Tanggal lahir perempuan ditambah 40 pada bagian tanggal.
     */
    public static function isValid(?string $nik): bool
    {
        $nik = trim((string) $nik);

        if (! preg_match('/^\d{16}$/', $nik)) {
            return false;
        }

        $province = (int) substr($nik, 0, 2);
        $district = substr($nik, 0, 6);
        $day = (int) substr($nik, 6, 2);
        $month = (int) substr($nik, 8, 2);
        $year = (int) substr($nik, 10, 2);

        if ($province < 11 || $province > 94 || $district === '000000') {
            return false;
        }

        if ($day > 40) {
            $day -= 40;
        }

        if ($day < 1 || $day > 31 || $month < 1 || $month > 12) {
            return false;
        }

        return checkdate($month, $day, 2000 + $year);
    }
}
